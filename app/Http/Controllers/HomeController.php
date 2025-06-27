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

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

use App\Models\Slide;
use App\Models\Footer;

use App\Models\Specialist;
use App\Models\Specialization;
use App\Models\SpecialistSpecialization;
use App\Models\Clinic;
use App\Models\SpecialistClinic;
use App\Models\Education;
use App\Models\SpecialistEducation;
use App\Models\Work;
use App\Models\SpecialistWork;
use App\Models\Certificate;
use App\Models\SpecialistCertificate;

use App\Models\About;

use App\Models\Contact;
use App\Models\Phone;
use App\Models\ContactPhone;
use App\Models\Mail;
use App\Models\ContactMail;
use App\Models\InfoFile;
use App\Models\Shedule;
use App\Models\ShedulesDay;
use App\Models\ShedulesDaysTime;
use App\Models\ShedulesClinic;
use App\Models\ShedulesCurrentDay;

use App\Models\PriceAddress;
use App\Models\PriceCategory;
use App\Models\PriceValue;

use App\Models\News;
use App\Models\Video;

use App\Models\Tracking;

class HomeController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                 БОТ ТЕЛЕГРАММ                     |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Отправка данных                                   */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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
   /* |                     О НАС                         |*/
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
            $infoFiles[$infoFilesKey]->date = Carbon::parse($infoFilesValue->created_at)->format('d.m.Y, H:i:s');
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
   /* |                    КОНТАКТЫ                       |*/
   /* |___________________________________________________|*/
   public function getContactsAll(Request $request)
   {
      try {
         $clinics = Clinic::all();
         $contacts = Contact::all();

         foreach ($contacts as $contactKey => $contactValue) {
            $contactPhones = ContactPhone::where('contactId', $contactValue->id)->get();
            $phones = [];

            foreach ($contactPhones as $contactPhonesKey => $contactPhonesValue) {
               $phones[] = Phone::where('id', $contactPhonesValue->phoneId)->first();
            };
            $contactValue->phones = $phones;

            $contactMails = ContactMail::where('contactId', $contactValue->id)->get();
            $mails = [];

            foreach ($contactMails as $contactMailsKey => $contactMailsValue) {
               $mails[] = Mail::where('id', $contactMailsValue->mailId)->first();
            };
            $contactValue->mails = $mails;
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => [
               "contacts" => $contacts,
               "clinics" => $clinics,
            ],
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

   /* Получение контактов и клиник */
   public function getContactsClinicsAll(Request $request)
   {
      try {
         $contacts = Contact::all();

         foreach ($contacts as $contactKey => $contactValue) {
            $contactPhones = ContactPhone::where('contactId', $contactValue->id)->get();
            $phones = [];

            foreach ($contactPhones as $contactPhonesKey => $contactPhonesValue) {
               $phones[] = Phone::where('id', $contactPhonesValue->phoneId)->first();
            };
            $contactValue->phones = $phones;

            $contactMails = ContactMail::where('contactId', $contactValue->id)->get();
            $mails = [];

            foreach ($contactMails as $contactMailsKey => $contactMailsValue) {
               $mails[] = Mail::where('id', $contactMailsValue->mailId)->first();
            };
            $contactValue->mails = $mails;
            $contactValue->clinic = Clinic::where('id', $contactValue->clinicId)->first();
         }

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $contacts,
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

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     НОВОСТИ                       |*/
   /* |___________________________________________________|*/
   /* Получение всех новостей */
   public function getNewsAll(Request $request)
   {
      try {
         $news = News::all()->sortByDesc('created_at')->values()->all();

         foreach ($news as $key => $value) {
            $value->date = Carbon::parse($value->created_at)->format('d.m.Y H:i:s');
            $value->url_date = Carbon::parse($value->created_at)->format('d.m.Y');
            $value->url_time = Carbon::parse($value->created_at)->format('H:i:s');
            $value->path = Storage::url('news/' . $value->image);
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
         "message" => "Данные получены.",
         "result" => $news,
      ], 200);
   }

   /* Получение всех новостей */
   public function getNewsShort(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'limit' => 'required|integer',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'integer' => 'Поле :attribute должно быть числом.',
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
         $news = News::all()->where('hide', 1)->sortByDesc('created_at')->take($request->limit)->values()->all();

         foreach ($news as $key => $value) {
            $value->date = Carbon::parse($value->created_at)->format('d.m.Y H:i:s');
            $value->url_date = Carbon::parse($value->created_at)->format('d.m.Y');
            $value->url_time = Carbon::parse($value->created_at)->format('H:i:s');
            $value->path = Storage::url('news/' . $value->image);
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
         "message" => "Данные получены.",
         "result" => [
            "news" => $news,
            "count" => News::all()->where('hide', 1)->count(),
         ],
      ], 200);
   }

   /* Получение одной новости */
   public function getNewsOnce(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'date' => 'required|date_format:d.m.Y',
         'time' => 'required|date_format:H:i:s',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'date_format' => 'Некорректный формат поля :attribute.',
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
         $news = News::whereDate('created_at', Carbon::parse($request->date))->whereTime('created_at', '=', $request->time)->first();
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);
      };

      if (!$news) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Такой новости нет.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => [
            "id" => $news->id,
            "path" => Storage::url('news/' . $news->image),
            "image" => $news->image,
            "title" => $news->title,
            "description" => $news->description,
            "hide" => $news->hide,
         ],
      ], 200);
   }

   /* Получение одной новости */
   public function getNewsOnceWithout(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'date' => 'required|date_format:d.m.Y',
         'time' => 'required|date_format:H:i:s',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'date_format' => 'Некорректный формат поля :attribute.',
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
         $news = News::whereDate('created_at', Carbon::parse($request->date))->whereTime('created_at', '=', $request->time)->first();

         if (!$news->hide) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Такой новости нет.",
               "result" => null,
            ], 500);
         }
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);
      };

      if (!$news) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Такой новости нет.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Новости получены.",
         "result" => [
            "id" => $news->id,
            "path" => Storage::url('news/' . $news->image),
            "image" => $news->image,
            "title" => $news->title,
            "description" => $news->description,
            "hide" => $news->hide,
         ],
      ], 200);
   }

   /* Получение одной новости */
   public function getNewsMore(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'date' => 'required|date_format:d.m.Y',
         'time' => 'required|date_format:H:i:s',
         'limit' => 'required|integer',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'date_format' => 'Некорректный формат поля :attribute.',
         'integer' => 'Поле :attribute должно быть числом.',
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

      $date = Carbon::parse($request->date . ' ' . $request->time);

      try {
         $news = News::where('created_at', '<', $date)->where('hide', 1)->get();

         $newsSorted = $news->sortByDesc('created_at')->take($request->limit)->values()->all();

         foreach ($newsSorted as $key => $value) {
            $value->date = Carbon::parse($value->created_at)->format('d.m.Y H:i:s');
            $value->url_date = Carbon::parse($value->created_at)->format('d.m.Y');
            $value->url_time = Carbon::parse($value->created_at)->format('H:i:s');
            $value->path = Storage::url('news/' . $value->image);
         };
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Новости получены.",
         "result" => [
            "news" => $newsSorted,
            "count" => News::all()->where('hide', 1)->count(),
         ],
      ]);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      ВИДЕО                        |*/
   /* |___________________________________________________|*/
   /* Получение всех видео */
   public function getVideosAll(Request $request)
   {
      try {
         $videos = Video::all()->sortBy('order')->values();

         foreach ($videos as $key => $value) {
            $value->path = Storage::url('video/' . $value->video);
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
