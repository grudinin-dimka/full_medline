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
use App\Models\About;
use App\Models\InfoFile;

class AboutController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   public function getAboutsAll(Request $request)
   {
      try {
         $about = About::all();

         foreach ($about as $key => $value) {
            $value->pathOne = Storage::url('abouts/' . $value->imageOne);
            $value->pathTwo = Storage::url('abouts/' . $value->imageTwo);
            $value->pathThree = Storage::url('abouts/' . $value->imageThree);
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $about,
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

   public function  getInfoFilesAll(Request $request)
   {
      try {
         $infoFiles = InfoFile::all();

         foreach ($infoFiles as $infoFilesKey => $infoFilesValue) {
            $infoFiles[$infoFilesKey]->path = Storage::url('files/' . $infoFilesValue->filename);
            $infoFiles[$infoFilesKey]->date = $infoFilesValue->created_at;
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $infoFiles,
         ]);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ]);
      }
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      POST                         |*/
   /* |___________________________________________________|*/
   public function saveAboutsChanges(Request $request)
   {
      $abouts = json_decode($request->abouts);
      $arrayID = [];

      DB::beginTransaction();

      try {
         foreach ($abouts as $key => $value) {
            // Удаление
            if ($value->delete === true) {
               $about = About::find($value->id);
               $about->delete();
               continue;
            };

            // Добавление
            if ($value->create === true) {
               $aboutCreate = About::create([
                  "order" => $value->order,
                  "title" => $value->title,
                  "description" => $value->description,
                  "imageOne" => $value->imageOne,
                  "imageTwo" => $value->imageTwo,
                  "imageThree" => $value->imageThree,
               ]);

               /* Запись нового объекта в массив */
               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value->id,
                  // Новый id
                  'new' => $aboutCreate->id
               ];
               continue;
            };

            // Обновление
            $about = About::find($value->id);
            $about->update([
               "order" => $value->order,
               "title" => $value->title,
               "description" => $value->description,
               "imageOne" => $value->imageOne,
               "imageTwo" => $value->imageTwo,
               "imageThree" => $value->imageThree,
            ]);
         };

         // Сортировка слайдов по порядку от наименьшего до наибольшого
         $aboutsAll = About::all()->SortBy('order');

         // Обновление порядковых номеров
         $count = 0;
         foreach ($aboutsAll as $aboutKey => $aboutValue) {
            $count++;
            $aboutValue->order = $count;
            $aboutValue->save();
         };

         // Получение всех файлов
         $filesAbouts = Storage::files('public/abouts');
         if ($filesAbouts) {
            foreach ($filesAbouts as $fileKey => $fileValue) {
               $useFile = false;
               /* Проверка на использование файла */
               foreach ($aboutsAll as $aboutKey => $aboutValue) {
                  /* Обрезание значения $fileValue до названия файла */
                  $str = str_replace('public/abouts/', '', $fileValue);
                  /* Проверка значения названия файла на совпадение */
                  if (
                     $aboutValue->imageOne == $str ||
                     $aboutValue->imageTwo == $str ||
                     $aboutValue->imageThree == $str
                  ) {
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
            "result" => $arrayID,
         ]);
      } catch (Throwable $e) {
         DB::rollBack();

         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      };
   }

   /* Сохранение данных файлов */
   public function saveInfoFilesChanges(Request $request)
   {
      $infoFiles = json_decode($request->infoFiles);
      $arrayID = [];

      DB::beginTransaction();

      try {
         foreach ($infoFiles as $key => $value) {
            // Удаление
            if ($value->delete === true) {
               $priceFile = InfoFile::find($value->id);
               $priceFile->delete();
               continue;
            }

            // Создание
            if ($value->create === true) {
               $infoFileCreate = InfoFile::create([
                  "filename" => $value->filename,
               ]);

               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value->id,
                  // Новый id
                  'new' => $infoFileCreate->id
               ];
               continue;
            };
         };

         $infoFiles = InfoFile::all();

         // Получение всех файлов
         $filesPrices = Storage::files('public/files');

         if ($filesPrices) {
            foreach ($filesPrices as $fileKey => $fileValue) {
               $useFile = false;
               // Проверка на использование файла
               foreach ($infoFiles as $infoFilesKey => $infoFilesValue) {
                  // Обрезание значения $fileValue до названия файла
                  $str = str_replace('public/files/', '', $fileValue);

                  // Проверка значения названия файла на совпадение
                  if ($infoFilesValue->filename == $str) {
                     $useFile = true;
                  };
               };

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
            "result" => $arrayID,
         ]);
      } catch (Throwable $e) {
         DB::rollBack();

         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      };
   }
}
