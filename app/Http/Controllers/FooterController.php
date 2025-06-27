<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Подключения */
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

/* Модели */
use App\Models\Footer;

/* Ошибки */
use Throwable;

class FooterController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   /* Получение данных о футере */
   public function getFooter(Request $request)
   {
      $footer = Footer::find(1);

      if (!$footer) {
         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Футер не найден.",
            "result" => null,
         ], 500);
      }

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Футер найден.",
         "result" => $footer->description,
      ], 200);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      POST                         |*/
   /* |___________________________________________________|*/
   /* Сохранение футера */
   public function saveFooter(Request $request)
   {
      // Валидация
      $validator = Validator::make($request->all(), [
         'description' => 'required|string',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'string' => 'Поле :attribute должно быть строкой.',
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
         $footer = Footer::find(1);

         $footerUpdate = $footer->update([
            'description' => $request->description,
         ]);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Футер не найден.",
            "result" => null
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Данные обновлены.",
         "data" => null
      ], 200);
   }
}
