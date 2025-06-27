<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

use App\Models\Clinic;

use App\Models\About;

use App\Models\Contact;
use App\Models\Phone;
use App\Models\ContactPhone;
use App\Models\Mail;
use App\Models\ContactMail;
use App\Models\InfoFile;

use App\Models\News;
use App\Models\Video;

use App\Models\Tracking;

class HomeController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                 БОТ ТЕЛЕГРАММ                     |*/
   /* |___________________________________________________|*/
   /* Отправка заявки в бота */
   public function requestTelegramBot(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'title' => 'required',
         'name' => 'required',
         'phone' => 'required',
         'description' => 'nullable',
      ], [
         'title' => 'Тип обязателен для заполнения.',
         'name' => 'ФИО обязательно для заполнения.',
         'phone' => 'Номер телефона обязателен для заполнения.',
         'description' => 'Описание обязательно для заполнения.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "error" => $validator->errors(),
            "message" => "Не все поля заполнены.",
            "result" => null,
         ], 422);
      };

      try {
         // Добавление заголовков
         $request->merge([
            'type' => 'form_request_telegram_bot',
            'meta' => 'Заявка - ' . $request->title,
         ]);

         $this->createTrack($request);

         // Отправка сообщения
         $str =
            "Заявка: " . $request->title . "\n" .
            "ФИО : " . $request->name . "\n" .
            "Телефон : " . $request->phone . "\n" .
            "Описание : " . $request->description . "\n";

         $ch = curl_init("https://api.telegram.org/bot" . "6465405714:AAHJTFfNkmKgSwtOgQHV1HxAZovcalaAbNU" . "/sendMessage?" . http_build_query(
            [
               "chat_id" => '6348437826',
               "text" => $str,
            ]
         ));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         curl_setopt($ch, CURLOPT_HEADER, false);
         if (curl_exec($ch)) {
            curl_close($ch);

            return response()->json([
               "success" => true,
               "debug" => true,
               "message" => "Заявка отправлена, ожидайте звонка оператора.",
               "result" => null,
            ], 200);
         } else {
            curl_close($ch);

            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Сервис отправки не работает.",
               "result" => null,
            ], 500);
         };
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Произошла ошибка.",
            "result" => null,
         ], 50);
      }
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    СТАТИСТИКА                     |*/
   /* |___________________________________________________|*/
   public function createTrack(Request $request)
   {
      try {
         // Поиск браузера
         $browser = match (true) {
            str_contains($request->userAgent(), 'Edge') => 'Edge',
            str_contains($request->userAgent(), 'Opera') => 'Opera',
            str_contains($request->userAgent(), 'Firefox') => 'Firefox',
            str_contains($request->userAgent(), 'Chrome') => 'Chrome',
            str_contains($request->userAgent(), 'Safari') => 'Safari',
            default => 'Unknown',
         };

         $track = Tracking::create([
            "type" => $request->type ?? "not defined",
            "ip" => $request->ip() ?? "not defined",
            "user_agent" => $browser ?? "not defined",
            "meta" => $request->meta ?? "not defined",
         ]);
      } catch (Throwable $e) {
         return response()->json([
            "status" => false,
            "message" => "Произошла ошибка при загрузке.",
            "data" => null,
         ]);
      };

      return response()->json([
         "status" => true,
         "message" => "Данные успешно получены.",
         "data" => false,
      ]);
   }
};
