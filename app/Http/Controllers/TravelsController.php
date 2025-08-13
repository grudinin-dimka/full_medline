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
use App\Models\TravelsServices;
use App\Models\TravelsPrices;

class TravelsController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   /* Получение всех новостей */
   public function getTravels(Request $request)
   {
      try {
         $travels = Travels::all()->where('hide', '=', false);

         foreach ($travels as $key => $value) {
            $value->path = $value->path();
            $value->services = $value->services;

            $groupedPrices = [];

            // Группировка цен по типу
            foreach ($value->prices as $priceKey => $priceValue) {
               $groupedPrices[$priceValue->type][] = $priceValue;
            }

            ksort($groupedPrices);

            $value->food = $groupedPrices;
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

               foreach ($value['services'] as $serviceKey => $serviceValue) {
                  $this->createService(
                     $serviceValue['title'],
                     $serviceValue['description'],
                     $travelCreate->id
                  );
               };

               foreach ($value['prices'] as $serviceKey => $serviceValue) {
                  $this->createPrice(
                     $serviceValue['type'],
                     $serviceValue['subtype'],
                     $serviceValue['price'],
                     $travelCreate->id
                  );
               };

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

            $travel->update([
               'title' => $value['title'],
               'duration' => $value['duration'],
               'description' => $value['description'],
               'hide' => $value['hide'],
               'image' => $value['image'],
               'order' => $value['order'],
            ]);

            $travel->services()->delete();

            foreach ($value['services'] as $serviceKey => $serviceValue) {
               $this->createService(
                  $serviceValue['title'],
                  $serviceValue['description'],
                  $travel->id
               );
            };

            $travel->prices()->delete();

            foreach ($value['prices'] as $serviceKey => $serviceValue) {
               $this->createPrice(
                  $serviceValue['type'],
                  $serviceValue['subtype'],
                  $serviceValue['price'],
                  $travel->id
               );
            };
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

   public function createService($title, $description, $travelId)
   {
      $serviceCreate = TravelsServices::create([
         'title' => $title,
         'description' => $description,
         'travel_id' => $travelId
      ]);
   }

   public function createPrice($type, $subtype, $price, $travelId)
   {
      $priceCreate = TravelsPrices::create([
         'type' => $type,
         'subtype' => $subtype,
         'price' => $price,
         'travel_id' => $travelId
      ]);
   }
}
