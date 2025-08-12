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
use App\Models\Travels;

class TravelsController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   /* Получение всех новостей */
   public function getTravelsAll(Request $request)
   {
      try {
         $travels = Travels::all();

         foreach ($travels as $key => $value) {
            $value->path = $value->path();
            $value->services = $value->services;
            $value->prices = $value->prices;
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $travels,
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
   /* Сохранение содержимого всех слайдов */
   public function saveTravelsChanges(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'travels' => 'nullable|array',
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

         foreach ($request->travels as $key => $value) {
            // Удаление
            if ($value['delete'] === true) {
               $travel = Travels::find($value['id']);
               $travel->delete();
               continue;
            };

            // Добавление
            if ($value['create'] === true) {
               $travelCreate = Travels::create([
                  'title' => $value['title'],
                  'duration' => $value['duration'],
                  'description' => $value['description'],
                  'image' => $value['image'],
                  'order' => $value['order'],
               ]);

               /* Запись нового объекта в массив */
               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value['id'],
                  // Новый id
                  'new' => $travelCreate->id
               ];
               continue;
            };

            // Обновление
            $travel = Travels::find($value['id']);

            $travelUpdate = $travel->update([
               'title' => $value['title'],
               'duration' => $value['duration'],
               'description' => $value['description'],
               'image' => $value['image'],
               'order' => $value['order'],
            ]);
         };

         // Сортировка слайдов по порядку от наименьшего до наибольшого
         $travels = Travels::all()->SortBy('order');

         // Обновление порядковых номеров
         $count = 0;

         foreach ($travels as $travelKey => $travelValue) {
            $count++;
            $travelValue->order = $count;
            $travelValue->save();
         };

         // Получение всех файлов
         $filesTravels = Storage::files('public/travels');

         if ($filesTravels) {
            foreach ($filesTravels as $fileKey => $fileValue) {
               $useFile = false;

               /* Проверка на использование файла */
               foreach ($travels as $travelKey => $travelValue) {
                  /* Обрезание значения $fileValue до названия файла */
                  $str = str_replace('public/travels/', '', $fileValue);

                  /* Проверка значения названия файла на совпадение */
                  if ($travelValue->filename == $str) {
                     $useFile = true;
                  };
               };

               /* Удаление файла, если не используется */
               if (!$useFile) {
                  Storage::delete($fileValue);
               };
            };
         };

         DB::commit();

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Данные обновлены.",
            "result" => $arrayID ?? [],
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
