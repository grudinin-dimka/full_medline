<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Подключения */
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/* Модели */
use App\Models\User\User;
use App\Models\User\Rights;
use App\Models\User\Status;
use App\Models\Tracking;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

/* Ошибки */
use Throwable;

class CreatorController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                 ПОЛЬЗОВАТЕЛИ                      |*/
   /* |___________________________________________________|*/
   /* Получение списка пользователей */
   public function getUsersAll(Request $request) {
      try {         
         $users = User::all();
         
         foreach ($users as $userKey => $userValue) {
               $rights = $userValue->rights();
               $status = $userValue->status();
   
               $userValue->path = Storage::url('users/' . $userValue->filename);
         };
   
         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => [
               "users" => $users,
               "rights" => Rights::all(),
               "statuses" => Status::all(),
            ],
         ]);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      };
   }

   /* Сохранение информации о пользователе */
   public function createUser(Request $request) {
      // Валидация
      $validator = Validator::make($request->all(), [
         'user' => 'required',
         'image' => [
            'required',
            File::image()
               ->types(['jpg', 'jpeg', 'png', 'webp'])
               ->max(5 * 1024)
               ->dimensions(Rule::dimensions()->maxWidth(1500)->maxHeight(1500)),
         ],
      ], [
         "required" => "Это поле обязательно к заполнению.",
         "image.types" => "Тип изображения не соответствует требованиям.",
         "image.max" => "Размер изображения не должен превышать 5мб.",
         "image.dimensions" => "Максимальное разрешение 1500x1500px.",
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

      $userData = json_decode($request->user, true);

      // Проверка данных пользователя
      $validatorUser = Validator::make($userData, [
         'email' => [
            'required',
            Rule::unique('users', 'email')
         ],
         'nickname' => [
            'required',
            Rule::unique('users', 'nickname')
         ],
         'family' => 'required',
         'name' => 'required',
         'dateOfBirth' => 'required',
         'statusId' => 'required',
         'rightsId' => 'required',
      ], [
         "email.unique" => "Почта должна быть уникальна.",
         "nickname.unique" => "Никнейм должен быть уникальным.",
         "required" => "Поле :attribute обязательно к заполнению.",
      ]);

      if ($validatorUser->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validatorUser->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };      

      try {
         $path = $request->file('image')->store(
            'public/users'
         );

         $user = User::create([
            'family' => $userData['family'],
            'name' => $userData['name'],
            'surname' => $userData['surname'] ? $userData['surname'] : null,
            'nickname' => $userData['nickname'],
            'dateOfBirth' => $userData['dateOfBirth'],
            'email' => $userData['email'],
            'statusId' => $userData['statusId'],
            'rightsId' => $userData['rightsId'],
            'password' => Hash::make($userData['password']),
            'filename' => basename($path),
         ]);
         
         $user->path = Storage::url($path);

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Пользователь создан.",
            "result" => $user,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);         
      }
   }

   /* Сохранение информации о пользователе */
   public function saveUser(Request $request) {
      // Валидация
      $validator = Validator::make($request->all(), [
         'user' => 'required',
      ], [
         "required" => "Это поле обязательно к заполнению.",
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

      $path = null;

      if ($request->hasFile('image')) {
         $validatorFile = Validator::make($request->all(), [
            'image' => [
               'required',
               File::image()
                  ->types(['jpg', 'jpeg', 'png', 'webp'])
                  ->max(5 * 1024)
                  ->dimensions(Rule::dimensions()->maxWidth(1500)->maxHeight(1500)),
            ],
         ], [
            "required" => "Это поле обязательно к заполнению.",
            "image.types" => "Тип изображения не соответствует требованиям.",
            "image.max" => "Размер изображения не должен превышать 5мб.",
            "image.dimensions" => "Максимальное разрешение 1500x1500px.",
         ]);

         if ($validatorFile->fails()) return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validatorFile->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);

         $path = $request->file('image')->store(
            'public/users'
         );
      };

      $userData = json_decode($request->user, true);

      // Проверка данных пользователя
      $validatorUser = Validator::make($userData, [
         'id' => [
            'required',
            Rule::exists('users', 'id'),
         ],
         'email' => [
            'required',
            Rule::unique('users', 'email')->ignore($userData['id'], 'id')
         ],
         'nickname' => [
            'required',
            Rule::unique('users', 'nickname')->ignore($userData['id'], 'id')
         ],
         'family' => 'required',
         'name' => 'required',
         'dateOfBirth' => 'required',
         'statusId' => 'required',
         'rightsId' => 'required',
      ], [
         "id.exists" => "Пользователь не найден.",
         "email.unique" => "Пользователь с такой почтой уже существует.",
         "nickname.unique" => "Пользователь с таким никнеймом уже существует.",
         "required" => "Поле :attribute обязательно к заполнению.",
      ]);

      if ($validatorUser->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validatorUser->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };      
      
      try {
         $user = User::find($userData['id']);

         // Проверка прав доступа
         if ($user->isCreator() && Rights::where('id', $userData['rightsId'])->first()->name !== 'creator') {
            $users = User::where('rightsId', $user->rightsId)->get();

            if ((count($users) - 1) < 1) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => "В системе должен быть хотя бы 1 создатель.",
                  "result" => null,            
               ], 422);
            }
         }        

         $user->family = $userData['family'];
         $user->name = $userData['name'];
         $user->surname = $userData['surname'];
         $user->dateOfBirth = $userData['dateOfBirth'];
         $user->nickname = $userData['nickname'];
         $user->email = $userData['email'];
         $user->statusId = $userData['statusId'];
         $user->rightsId = $userData['rightsId'];
         if ($path) $user->filename = basename($path);
         if (!empty($userData['password'])) $user->password = Hash::make($userData['password']);
         $user->save();            
         
         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Данные обновлены.",
            "result" => [
               "path" => $path ? Storage::url($path) : null,
            ],
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);         
      };
   }
   
   /* Удаление пользователя */
   public function deleteUser(Request $request) {
      $validator = Validator::make($request->all(), [
         'id' => [
            'required',
            Rule::exists('users', 'id'),
         ],
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'exists' => 'Такого пользователя не существует.',
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

      // Проверка на пользователя
      if($request->user()->id === $request->id) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Нельзя удалить самого себя.",
            "result" => null,
         ], 422);
      };

      $user = User::find($request->id);

      // Проверка прав доступа
      if ($user->isCreator()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Нельзя удалить создателя.",
            "result" => null,
         ], 422);
      };

      try {
         $user->delete();
     
            return response()->json([
               "success" => true,
               "debug" => true,
               "message" => "Пользователь удалён.",
               "result" => null,
            ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      };   
   }

   /* Установка нового пароля */
   public function setUserPassword(Request $request) {
      $validator = Validator::make($request->all(), [
         'id' => [
            'required',
            Rule::exists('users', 'id'),
         ],
         'password' => 'required',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'exists' => 'Такого пользователя не существует.',
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

      try {
         $user = User::find($request->id);
         $user->password = Hash::make($request->password);
         $user->save();

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Пароль обновлен.",
            "result" => null,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);         
      };
   }
   
   /* Установка нового статуса */
   public function setUserStatus(Request $request) {
      $validator = Validator::make($request->all(), [
         'id' => [
            'required',
            Rule::exists('users', 'id'),
         ],
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'exists' => 'Такого пользователя не существует.',
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

      $user = User::find($request->id);
      
      if ($request->user()->id === $user->id) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Нельзя изменить статус самому себе.",
            "result" => null,
         ]);
      };

      try {
         $user->statusId = $user->statusId === 1 ? 2 : 1;
         $user->save();

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Статус обновлен.",
            "result" => $user->statusId,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);         
      };
   }    

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    СТАТИСТИКА                     |*/
   /* |___________________________________________________|*/
   public function getTrackingStatisticsList(Request $request) {
      try {
         $statistics = Tracking::all()->reverse()->values()->take(1000);

         foreach ($statistics as $key => $statistic) {
            $date = Carbon::parse($statistic->created_at);

            // Форматируем дату в нужный формат
            $dateShort = $date->format('d.m.Y H:i:s');

            $statistic->created = $dateShort;
         };
         
         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $statistics,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      };
   }

   public function getTrackingStatisticsRange(Request $request) {
      $validator = Validator::make($request->all(), [
         'start' => 'required|date',
         'end' => 'required|date',
      ], [
         'required' => 'Поле :attribute обязательно.',
         'date' => 'Формат :attribute неверен.',
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

      try {
         if ($request->type) {
            $statistics = Tracking::all()
            ->where('created_at', '>=', Carbon::parse($request->start))
            ->where('created_at', '<=', Carbon::parse($request->end)->addDay())
            ->where('type', $request->type)
            ->groupBy(
               function($item) {
                  return $item->created_at->format('Y-m-d'); // Группируем только по дате (без времени)
               }
            );

            $statisticsFormated = $statistics;

            $countDays = Carbon::parse($request->start)->diffInDays(Carbon::parse($request->end));
            $statisticsFormated = [];
            $firsDay = Carbon::parse($request->start);

            for ($i = $countDays; $i >= 0; $i--) {
               $statisticsFormated[Carbon::parse($firsDay)->format('Y-m-d')] = $statistics[Carbon::parse($firsDay)->format('Y-m-d')] ?? [];

               $firsDay->addDay();
            };
         } else {
            $statistics = Tracking::all()
               ->where('created_at', '>=', Carbon::parse($request->start))
               ->where('created_at', '<=', Carbon::parse($request->end)->addDay())
               ->groupBy([
                  'type',
                  function($item) {
                     return $item->created_at->format('Y-m-d'); // Группируем только по дате (без времени)
                  }, 
               ]);
         
            $countDays = Carbon::parse($request->start)->diffInDays(Carbon::parse($request->end));
            $statisticsFormated = [];
            $firsDay = Carbon::parse($request->start);

            foreach ($statistics as $key => $statistic) {
               $statisticsFormated[$key] = [];
            };

            for ($i = $countDays; $i >= 0; $i--) {
               foreach ($statistics as $key => $statistic) {
                  $statisticsFormated[$key][Carbon::parse($firsDay)->format('Y-m-d')] = $statistic[Carbon::parse($firsDay)->format('Y-m-d')] ?? [];
               } 

               $firsDay->addDay();
            };
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные успешно получены.",
            "result" => $statisticsFormated,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      };
   }
}
