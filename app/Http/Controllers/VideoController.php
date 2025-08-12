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
use App\Models\Video;

class VideoController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   /* Получение всех видео */
   public function getVideosAll(Request $request)
   {
      try {
         $videos = Video::all()->sortBy('order')->values();

         foreach ($videos as $key => $value) {
            $value->path = $value->path();
         };
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);
      }

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Не удалось получить данные.",
         "result" => $videos,
      ], 200);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      POST                         |*/
   /* |___________________________________________________|*/
   /* Добавление */
   public function saveVideoChanges(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'videos' => 'nullable|array',
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
         ]);
      };

      // Транзакция
      DB::beginTransaction();

      try {
         $arrayID = [];

         foreach ($request->videos as $key => $value) {
            // Удаление
            if ($value["delete"] === true) {
               $video = Video::find($value["id"]);
               $video->delete();

               continue;
            };

            // Добавление
            if ($value["create"] === true) {
               $videoCreate = Video::create([
                  "order" => $value["order"],
                  "video" => $value["video"],
                  "description" => $value["description"],
               ]);

               /* Запись нового объекта в массив */
               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value["id"],
                  // Новый id
                  'new' => $videoCreate->id
               ];
               continue;
            };

            // Обновление
            $video = Video::find($value["id"]);
            $video->update([
               "order" => $value["order"],
               "video" => $value["video"],
               "description" => $value["description"],
            ]);
         };

         // Сортировка слайдов по порядку от наименьшего до наибольшого
         $videosAll = Video::all()->SortBy('order');

         // Обновление порядковых номеров
         $count = 0;
         foreach ($videosAll as $videoKey => $videoValue) {
            $count++;
            $videoValue["order"] = $count;
            $videoValue->save();
         };

         // Получение всех файлов
         $filesVideos = Storage::files('public/video');
         if ($filesVideos) {
            foreach ($filesVideos as $fileKey => $fileValue) {
               $useFile = false;
               /* Проверка на использование файла */
               foreach ($videosAll as $aboutKey => $videoValue) {
                  /* Проверка значения названия файла на совпадение */
                  if ($videoValue->video == basename($fileValue)) {
                     $useFile = true;
                  };
               };

               /* Удаление файла, если не используется */
               if (!$useFile) {
                  Storage::delete($fileValue);
               };
            };
         };

         // Фиксация транзакции
         DB::commit();

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Данные обновлены.",
            "result" => $arrayID,
         ]);
      } catch (Throwable $e) {
         // Отмена транзакции
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
