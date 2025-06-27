<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Подключения */
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

/* Ошибки */
use Throwable;

/* Помощники */
use Carbon\Carbon;

/* Модели */
use App\Models\Specialist\Specialist;

use App\Models\Shedule\Shedule;
use App\Models\Shedule\ShedulesClinic;
use App\Models\Shedule\ShedulesCurrentDay;
use App\Models\Shedule\ShedulesDay;
use App\Models\Shedule\ShedulesDaysTime;

class SheduleController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   /* Получение расписания */
   public function getShedulesAll(Request $request)
   {
      try {

         // Загрузка ВСЕХ основных данных сразу 
         $schedules = Shedule::all();
         $scheduleClinics = ShedulesClinic::all();
         $currentDays = ShedulesCurrentDay::all();

         // Подготовка ID для массовой загрузки связанных данных
         $scheduleIds = $schedules->pluck('id');
         $clinicIds = $scheduleClinics->pluck('id');

         // Загрузка ВСЕХ дней расписаний за ОДИН запрос
         $scheduleDays = ShedulesDay::whereIn('sheduleId', $scheduleIds)
            ->whereIn('clinicId', $clinicIds)
            ->get()
            ->groupBy(['sheduleId', 'clinicId']);


         // Загрузка ВСЕХ времен для дней за ОДИН запрос
         $dayIds = $scheduleDays->flatten()->pluck('id'); // Получаем все ID дней
         $scheduleTimes = ShedulesDaysTime::whereIn('dayId', $dayIds)
            ->get()
            ->groupBy('dayId');

         $specialists = Specialist::all()->groupBy(function ($specialist) {
            return $specialist->family . ' ' . $specialist->name . ' ' . $specialist->surname;
         });

         // Создаем "карту" текущих дней для мгновенного поиска по дате
         $currentDaysMap = $currentDays->keyBy('date');

         // Основной цикл по расписаниям
         foreach ($schedules as $schedule) {
            $weeks = []; // Здесь будем хранить данные для каждой клиники

            /* Получение специалистов */
            $sheduleSpecialits = $specialists[$schedule->name] ?? null;
            if ($sheduleSpecialits) {
               $schedule->image = Storage::url('specialists/' . $sheduleSpecialits[0]->filename);
               $schedule->link = $this->makeUrl($sheduleSpecialits[0]->family . ' ' . $sheduleSpecialits[0]->name . ' ' . $sheduleSpecialits[0]->surname);
            } else {
               $schedule->image = null;
            }

            // Цикл по клиникам для текущего расписания
            foreach ($scheduleClinics as $clinic) {
               // Получаем дни для данной пары (расписание + клиника)
               // Используем группировку, чтобы избежать дополнительных запросов
               $daysForClinic = $scheduleDays
                  ->get($schedule->id, collect()) // Ищем по ID расписания
                  ->get($clinic->id, collect());  // Затем по ID клиники

               // Если есть дни для этой клиники
               if ($daysForClinic->isNotEmpty()) {
                  // Формируем контент для всех текущих дней
                  $content = $currentDays->map(function ($day) use ($daysForClinic, $scheduleTimes) {
                     // Ищем день с такой же датой
                     $matchingDay = $daysForClinic->firstWhere('date', $day->date);

                     // Формируем объект дня
                     return [
                        "id" => $day->id,
                        "date" => $day->date,
                        "time" => $matchingDay
                           ? $scheduleTimes->get($matchingDay->id, collect())->pluck('name')->all()
                           : [], // Если нет совпадения - пустой массив
                     ];
                  })->all();

                  $weeks[] = (object) [
                     "clinicId" => $clinic->id,
                     "status" => true,
                     "content" => $content,
                  ];
               } else {
                  // Если нет дней для этой клиники
                  $weeks[] = (object) [
                     "clinicId" => $clinic->id,
                     "status" => false,
                     "content" => $currentDays->map(function ($day) {
                        return [
                           "id" => $day->id,
                           "date" => $day->date,
                           "time" => [],
                        ];
                     })->all(),
                  ];
               }
            }

            // Сохраняем сформированные данные в объект расписания
            $schedule->weeks = $weeks;
         }

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => [
               "currentDays" => $currentDays,
               "shedules" => $schedules,
               "sheduleClinics" => $scheduleClinics,
               "specialists" => $specialists,
            ],
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => false,
            "message" => "Произошла ошибка.",
            "result" => null,
         ], 500);
      };
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      POST                         |*/
   /* |___________________________________________________|*/
   /* Сохранение расписания */
   public function saveShedulesAll($isManual = null)
   {
      // Получение всех файлов
      $files = Storage::disk('private')->files('schedules');

      // Сортировка массива файлов
      $jsonFiles = array_filter($files, function ($file) {
         return pathinfo($file, PATHINFO_EXTENSION) === 'json';
      });

      // Поиск самого актуального файла
      $lastFile = null;
      foreach ($jsonFiles as $key => $value) {
         if ($lastFile === null) {
            $lastFile = $value;
            continue;
         };

         $CurrentFileName = pathinfo($value, PATHINFO_FILENAME);
         $dateLastFileName = pathinfo($lastFile, PATHINFO_FILENAME);

         try {
            $currentFileDate = Carbon::createFromFormat('Y-m-d H-i', $CurrentFileName);
            $lastFileDate = Carbon::createFromFormat('Y-m-d H-i', $dateLastFileName);
         } catch (Throwable $e) {
            if ($isManual) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => $e->getMessage(),
                  "result" => null
               ], 500);
            }

            Log::error($e->getMessage());
            continue;
         }

         if ($currentFileDate > $lastFileDate) {
            $lastFile = $value;
            continue;
         };
      }

      // Если файл есть
      if ($lastFile) {
         $file = Storage::disk('private')->get($lastFile);
         $fileContent = json_decode($file);

         $clinics = $fileContent?->clinics;
         if (!$clinics) {
            if ($isManual) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => 'Отсутствуют клиники.',
                  "result" => null
               ], 500);
            }

            Log::error('Отсутствуют клиники.');
         }

         $week = $fileContent?->week;
         if (!$week) {
            if ($isManual) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => 'Отсутствуют дни.',
                  "result" => null
               ], 500);
            }

            Log::error('Отсутствуют дни.');
         }

         $shedules = $fileContent?->shedules;
         if (!$shedules) {
            if ($isManual) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => 'Отсутствует расписание.',
                  "result" => null
               ], 500);
            }

            Log::error('Отсутствует расписание.');
         }
      } else {
         if ($isManual) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => 'Отсутствует файл.',
               "result" => null
            ], 500);
         }

         Log::error('Отсутствует файл.');
      };

      // Сбрасываю ограничения внешнего ключа, чтобы очистить таблицы
      DB::statement('SET FOREIGN_KEY_CHECKS = 0');

      // Очищаю таблицы
      Shedule::truncate();
      ShedulesClinic::truncate();
      ShedulesCurrentDay::truncate();
      ShedulesDay::truncate();
      ShedulesDaysTime::truncate();

      // Возвращаю ограничения внешнего ключа 
      DB::statement('SET FOREIGN_KEY_CHECKS = 1');

      try {
         $clinicId = [];
         foreach ($clinics as $clinicsKey => $clinicsValue) {
            $clinic = ShedulesClinic::create([
               "name" => $clinicsValue->name,
            ]);

            $clinicId['' . $clinicsValue->id . ''] = $clinic->id;
         };

         $currentDays = [];
         foreach ($week as $weekKey => $weekValue) {
            $day = ShedulesCurrentDay::create([
               "date" => $weekValue->date,
            ]);

            $currentDays[] = (object) [
               "id" => $day->id,
               "date" => $weekValue->date,
            ];
         };

         foreach ($shedules as $shedulesKey => $shedulesValue) {
            $shedule = Shedule::create([
               "name" => preg_replace("/\s+/u", " ", trim($shedulesValue->name, ' ')),
               "specializations" => preg_replace("/\s+/u", " ", trim($shedulesValue->specializations, ' ')),
            ]);

            foreach ($shedulesValue->weeks as $sheduleWeekKey => $sheduleWeekValue) {
               if ($sheduleWeekValue->status === true) {
                  foreach ($sheduleWeekValue->content as $sheduleWeekContentKey => $sheduleWeekContentValue) {
                     $day = ShedulesDay::create([
                        "date" => $sheduleWeekContentValue->date,
                        "sheduleId" => $shedule->id,
                        "clinicId" => $clinicId[$sheduleWeekValue->clinicId],
                     ]);

                     foreach ($sheduleWeekContentValue->time as $ContentValueTimeKey => $ContentValueTimeValue) {
                        $time = ShedulesDaysTime::create([
                           "name" => $ContentValueTimeValue,
                           "dayId" => $day->id,
                        ]);
                     };
                  };
               };
            };
         };

         if ($isManual) {
            return response()->json([
               "success" => true,
               "debug" => true,
               "message" => 'Данные обновлены.',
               "result" => null
            ], 200);
         }
      } catch (Throwable $e) {
         if ($isManual) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => $e,
               "result" => null
            ], 500);
         }

         Log::error($e);
      }
   }
}
