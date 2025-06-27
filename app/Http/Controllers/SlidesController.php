<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Подключения */
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

/* Модели */
use App\Models\Slide;

/* Ошибки */
use Throwable;

class SlidesController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   /* Получение данных о всех слайдах */
   public function getSlidesAll(Request $request)
   {
      $slides = Slide::all();

      foreach ($slides as $key => $value) {
         // Добавление нового поля path, в котором хранится путь к изображению
         $slides[$key]->path = Storage::url('slides/' . $value->filename);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Слайды получены.",
         "result" => $slides,
      ], 200);
   }

   /* Получение данных о всех слайдах, которые не скрыты */
   public function getSlidesNotHide(Request $request)
   {
      $slides = Slide::where('hide', false)->get();
      foreach ($slides as $key => $value) {
         $slides[$key]->path = Storage::url('slides/' . $value->filename);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Слайды получены.",
         "result" => $slides,
      ], 200);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      POST                         |*/
   /* |___________________________________________________|*/
   /* Сохранение содержимого всех слайдов */
   public function saveSlidesChanges(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'slides' => 'nullable|array',
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

      $arrayID = [];

      foreach ($request->slides as $key => $value) {
         // Удаление
         if ($value['delete'] === true) {
            $slide = Slide::find($value['id']);
            $slide->delete();
            continue;
         };

         // Добавление
         if ($value['create'] === true) {
            $slideCreate = Slide::create([
               'name' => $value['name'],
               'link' => $value['link'],
               'filename' => $value['filename'],
               'hide' => $value['hide'],
               'order' => $value['order'],
            ]);

            /* Запись нового объекта в массив */
            $arrayID[] = (object) [
               // Прошлый id
               'old' => $value['id'],
               // Новый id
               'new' => $slideCreate->id
            ];
            continue;
         };

         // Обновление
         $slide = Slide::find($value['id']);
         $slideUpdate = $slide->update([
            'name' => $value['name'],
            'link' => $value['link'],
            'filename' => $value['filename'],
            'hide' => $value['hide'],
            'order' => $value['order'],
         ]);
      };

      // Сортировка слайдов по порядку от наименьшего до наибольшого
      $slides = Slide::all()->SortBy('order');

      // Обновление порядковых номеров
      $count = 0;
      foreach ($slides as $slideKey => $slideValue) {
         $count++;
         $slideValue->order = $count;
         $slideValue->save();
      };

      // Получение всех файлов
      $filesSlides = Storage::files('public/slides');
      if ($filesSlides) {
         foreach ($filesSlides as $fileKey => $fileValue) {
            $useFile = false;

            /* Проверка на использование файла */
            foreach ($slides as $slideKey => $slideValue) {
               /* Обрезание значения $fileValue до названия файла */
               $str = str_replace('public/slides/', '', $fileValue);

               /* Проверка значения названия файла на совпадение */
               if ($slideValue->filename == $str) {
                  $useFile = true;
               };
            };

            /* Удаление файла, если не используется */
            if (!$useFile) {
               Storage::delete($fileValue);
            };
         };
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Данные обновлены.",
         "result" => $arrayID
      ], 200);
   }
}
