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
use App\Models\Vacancies;

class VacanciesController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   /* Получение всех новостей */
   public function getVacancies(Request $request)
   {
      try {
         $vacancies = Vacancies::all()->where('hide', '=', false)->all();

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $vacancies,
         ], 200);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);
      }
   }

   /* Получение всех новостей */
   public function getVacanciesAll(Request $request)
   {
      try {
         $vacancies = Vacancies::all();

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $vacancies,
         ], 200);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);
      }
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      POST                         |*/
   /* |___________________________________________________|*/
   /* Сохранение содержимого */
   public function saveVacanciesChanges(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'vacancies' => 'nullable|array',
      ], [
         'array' => 'Поле :attribute должно быть массивом.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      DB::beginTransaction();

      try {
         $arrayID = [];

         foreach ($request->vacancies as $key => $value) {
            // Удаление
            if ($value['delete'] === true) {
               $vacancy = Vacancies::find($value['id']);
               $vacancy->delete();
               continue;
            };

            // Добавление
            if ($value['create'] === true) {
               $vacancyCreate = Vacancies::create([
                  'title' => $value['title'],
                  'address' => $value['address'],
                  'schedule' => $value['schedule'],
                  'requirements' => $value['requirements'],
                  'conditions' => $value['conditions'],
                  'description' => $value['description'],
                  'salary' => $value['salary'],
                  'hide' => $value['hide'],
               ]);

               /* Запись нового объекта в массив */
               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value['id'],
                  // Новый id
                  'new' => $vacancyCreate->id
               ];
               continue;
            };

            // Обновление
            $travel = Vacancies::find($value['id']);

            $travel->update([
               'title' => $value['title'],
               'address' => $value['address'],
               'schedule' => $value['schedule'],
               'requirements' => $value['requirements'],
               'conditions' => $value['conditions'],
               'description' => $value['description'],
               'salary' => $value['salary'],
               'hide' => $value['hide'],
            ]);
         };

         DB::commit();

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Данные обновлены.",
            "result" => $arrayID,
         ], 200);
      } catch (Throwable $th) {
         DB::rollBack();
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $th->getMessage(),
            "result" => null,
         ], 500);
      };
   }
}
