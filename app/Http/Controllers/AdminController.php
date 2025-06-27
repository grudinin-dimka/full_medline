<?php

namespace App\Http\Controllers;

/* Подключения */
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/* Помощники */
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

/* Модели */
use App\Models\Rights;
use App\Models\Status;
use App\Models\User;

use App\Models\Slide;
use App\Models\Footer;

use App\Models\About;
use App\Models\InfoFile;

use App\Models\Contact;
use App\Models\Phone;
use App\Models\ContactPhone;
use App\Models\Mail;
use App\Models\ContactMail;

use App\Models\Shedule;
use App\Models\ShedulesClinic;
use App\Models\ShedulesCurrentDay;
use App\Models\ShedulesDay;
use App\Models\ShedulesDaysTime;

use App\Models\News;
use App\Models\Video;

use Exception;
use Throwable;

use function PHPUnit\Framework\isEmpty;

class AdminController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     ОБЩИЕ                         |*/
   /* |___________________________________________________|*/
   /* Загрузка файла на сервер */
   public function uploadFile(Request $request)
   {
      /* Проверка на наличие переменной image с файлом в запросе */
      if ($request->hasFile('file')) {
         $file = $request->file('file');

         // Проверка на картинку
         $isImage = str_starts_with($file->getMimeType(), 'image/');

         // Проверка на видео
         $isVideo = str_starts_with($file->getMimeType(), 'video/');

         // Правила
         $rules = [
            'type' => 'required',
            'formats' => 'required',
         ];

         // Сообщения
         $messages = [
            'type.required' => 'Тип не указан.',
            'formats.required' => 'Форматы не указаны.',
         ];

         // Добавление правил в зависимости от типа
         if ($isImage) {
            $rules['file'] = [
               'required', // общие правила
               File::image()
                  ->types($request->formats)
                  ->dimensions(Rule::dimensions()->maxWidth(1500)->maxHeight(1500)),
               'max:' . (5 * 1024),
            ];

            $messages['file.required'] = 'Файл не указан.';
            $messages['file.types'] = 'Файл не соответствует форматам.';
            $messages['file.dimensions'] = 'Файл превышает допустимое разрешение 1500х1500.';
            $messages['file.max'] = 'Файл превышает допустимый размер 5 мб.';
         } else if ($isVideo) {
            $rules['file'] = [
               'required', // общие правила
               'mimetypes:video/mp4,video/quicktime,video/webm',
               'max:' . (100 * 1024),
            ];

            $messages['file.required'] = 'Файл не указан.';
            $messages['file.mimetypes'] = 'Файл не соответствует форматам.';
            $messages['file.max'] = 'Файл превышает допустимый размер 100 мб.';
         } else {
            $rules['file'] = [
               'required', // общие правила
               File::types($request->formats),
               'max:' . (2 * 1024),
            ];

            $messages['file.required'] = 'Файл не указан.';
            $messages['file.types'] = 'Файл не соответствует форматам.';
            $messages['file.max'] = 'Файл превышает допустимый размер 2 мб.';
         };

         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Файл не прошёл проверку.",
            "result" => null,
         ], 422);

         // Получение оригинального названия
         $originalName = $file->getClientOriginalName();

         switch ($request->type) {
            case 'prices':
               $path = $request->file('file')->storeAs(
                  'public/prices',
                  $originalName,
                  'local'
               );
               break;
            case 'files':
               $path = $request->file('file')->storeAs(
                  'public/files',
                  $originalName,
                  'local'
               );
               break;
            default:
               $path = $request->file('file')->store(
                  'public/' . $request->type
               );
               break;
         }

         if ($path) {
            return response()->json([
               "success" => true,
               "debug" => false,
               "message" => "Файл загружен.",
               "result" => Storage::url($path),
            ], 200);
         } else {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Файл не удалось загрузить.",
               "result" => null,
            ], 500);
         };
      } else {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Отсутствует файл.",
            "result" => null,
         ], 422);
      };
   }

   /* Получение данных о профиле пользователя */
   public function getProfileInfo(Request $request)
   {
      try {
         $user = $request->user();
         $user->status = Status::find($user->statusId)->name;
         $user->rights = Rights::find($user->rightsId)->name;
         $user->path = Storage::url('users/' . $user->filename);

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $user,
         ], 200);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $th->getMessage(),
            "result" => null,
         ], 500);
      }
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     О НАС                         |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Информационные блоки                              */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    КОНТАКТЫ                       |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Информационные блоки                              */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   public function saveContactsChanges(Request $request)
   {
      $contacts = json_decode($request->contacts);
      $arrayID = [];

      // Транзакция
      DB::beginTransaction();

      try {
         foreach ($contacts as $key => $value) {
            // Удаление
            if ($value->delete === true) {
               $contact = Contact::find($value->id);
               $contact->delete();
               continue;
            };

            // Добавление
            if ($value->create === true) {
               $contactCreate = Contact::create([
                  "name" => $value->name,
                  "order" => $value->order,
                  "clinicId" => $value->clinicId ? $value->clinicId : null,
               ]);

               // Телефоны
               $phones = ContactPhone::where('contactId', $value->id)->get();
               foreach ($phones as $phoneKey => $phoneValue) {
                  $phone = Phone::find($phoneValue->phoneId);
                  $phone->delete();
               };

               foreach ($value->phones as $key => $valuePhone) {
                  $phoneCreate = Phone::create([
                     "name" => $valuePhone->name,
                  ]);

                  ContactPhone::create([
                     'contactId' => $contactCreate->id,
                     'phoneId' => $phoneCreate->id,
                  ]);
               }

               // Почты
               $emails = ContactMail::where("contactId", $value->id)->get();
               foreach ($emails as $emailKey => $emailValue) {
                  $email = Mail::find($emailValue->mailId);
                  $email->delete();
               };

               foreach ($value->mails as $key => $valueEmail) {
                  $emailCreate = Mail::create([
                     "name" => $valueEmail->name,
                  ]);

                  ContactMail::create([
                     "contactId" => $contactCreate->id,
                     "mailId" => $emailCreate->id,
                  ]);
               }

               /* Запись нового объекта в массив */
               $arrayID[] = (object) [
                  // Прошлый id
                  "old" => $value->id,
                  // Новый id
                  "new" => $contactCreate->id
               ];
               continue;
            };

            // Обновление
            $contact = Contact::find($value->id);
            $contact->update([
               "name" => $value->name,
               "order" => $value->order,
               "clinicId" => ($value->clinicId == "null" || $value->clinicId == null) ? null : $value->clinicId,
            ]);

            // Телефоны
            $phones = ContactPhone::where("contactId", $value->id)->get();
            foreach ($phones as $phoneKey => $phoneValue) {
               $phone = Phone::find($phoneValue->phoneId);
               $phone->delete();
            };

            foreach ($value->phones as $key => $valuePhone) {
               $phoneCreate = Phone::create([
                  "name" => $valuePhone->name,
               ]);

               ContactPhone::create([
                  "contactId" => $contact->id,
                  "phoneId" => $phoneCreate->id,
               ]);
            }

            // Почты
            $emails = ContactMail::where('contactId', $value->id)->get();
            foreach ($emails as $emailKey => $emailValue) {
               $email = Mail::find($emailValue->mailId);
               $email->delete();
            };

            foreach ($value->mails as $key => $valueEmail) {
               $emailCreate = Mail::create([
                  "name" => $valueEmail->name,
               ]);

               ContactMail::create([
                  'contactId' => $contact->id,
                  'mailId' => $emailCreate->id,
               ]);
            }
         };

         // Сортировка слайдов по порядку от наименьшего до наибольшого
         $contactAll = Contact::all()->SortBy('order');

         // Обновление порядковых номеров
         $count = 0;
         foreach ($contactAll as $contactKey => $contactValue) {
            $count++;
            $contactValue->order = $count;
            $contactValue->save();
         };

         // Фиксация транзакции
         DB::commit();

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Новости изменены.",
            "result" => $arrayID,
         ], 200);
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

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    НОВОСТИ                        |*/
   /* |___________________________________________________|*/
   /* Добавление новости */
   public function addNews(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'image' => [
            'required',
            File::image()
               ->types(['png', 'webp', 'jpg', 'jpeg'])
               ->max(5 * 1024)
               ->dimensions(Rule::dimensions()->maxWidth(2000)->maxHeight(2000)),
         ],
         'title' => 'required|string',
         'description' => 'required|string',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'string' => 'Поле :attribute должно быть строкой.',
         'image.types' => 'Допустимые типы файлов: png, webp, jpg, jpeg.',
         'image.max' => 'Максимальный размер 5MB.',
         'image.dimensions' => 'Максимальное разрешение 2000x2000px.',
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

      $path = request()->file('image')->store(
         'public/news'
      );

      if (!$path) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось сохранить изображение.",
            "result" => null,
         ], 500);
      };

      try {
         $news = News::create([
            'image' => basename($path),
            'title' => $request->title,
            'description' => $request->description,
         ]);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось добавить новость.",
            "result" => null,
         ], 500);
      }

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Новость добавлена.",
         "result" => [
            "id" => $news->id,
            "path" => Storage::url($path),
            "image" => basename($path),
            "title" => $request->title,
            "description" => $request->description,
            "url_date" => Carbon::parse($news->created_at)->format('d.m.Y'),
            "url_time" => Carbon::parse($news->created_at)->format('H:i:s'),
         ],
      ], 200);
   }

   /* Изменение новости */
   public function saveNewsChangesOnce(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'id' => [
            'required',
            Rule::exists('news', 'id'),
         ],
         'title' => 'required|string',
         'description' => 'required|string',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'string' => 'Поле :attribute должно быть строкой.',
         'exists' => 'Такой новости не существует (:attribute).',
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
         $validatorImage = Validator::make($request->all(), [
            'image' => [
               'required',
               File::image()
                  ->types(['png', 'webp', 'jpg', 'jpeg'])
                  ->max(5 * 1024)
                  ->dimensions(Rule::dimensions()->maxWidth(2000)->maxHeight(2000)),
            ],
         ], [
            'image.required' => 'Файл не загружен.',
            'image.types' => 'Допустимые типы файлов: png, webp, jpg, jpeg.',
            'image.max' => 'Максимальный размер 5MB.',
            'image.dimensions' => 'Максимальное разрешение 2000x2000px.',
         ]);

         if ($validatorImage->fails()) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "errors" => $validatorImage->errors(),
               "message" => "Некорректные данные.",
               "result" => null,
            ], 422);
         };

         $path = request()->file('image')->store(
            'public/news'
         );

         if (!$path) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "errors" => $validator->errors(),
               "message" => "Не удалось сохранить изображение.",
               "result" => null,
            ], 500);
         };
      };

      $news = News::find($request->id);

      try {
         $slideUpdate = $news->update([
            "image" => $path ? basename($path) : $news->image,
            "title" => $request->title,
            "description" => $request->description,
         ]);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось изменить новость.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Новость изменена.",
         "result" => [
            "id" => $news->id,
            "path" => $path ? Storage::url($path) : Storage::url('news/' . $news->image),
            "image" => $path ? basename($path) : $news->image,
            "title" => $request->title,
            "description" => $request->description,
         ],
      ], 200);
   }

   /* Публикация новости */
   public function publishNewsOnce(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'id' => [
            'required',
            Rule::exists('news', 'id'),
         ],
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'exists' => 'Такой новости не существует (:attribute).',
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

      $news = News::find($request->id);
      $message = $news->hide ? 'Снято с публикации.' : 'Опубликовано.';

      try {
         $newsUpdate = $news->update([
            "hide" => !$news->hide,
         ]);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось опубликовать новость.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => $message,
         "result" => $news->hide,
      ], 200);
   }

   /* Изменение новости */
   public function saveNewsChangesAll(Request $request)
   {
      $validator = Validator::make($request->all(), [
         'news' => 'nullable|array',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
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

      // Транзакция
      DB::beginTransaction();

      try {
         // Удаление
         foreach ($request->news as $key => $value) {
            $news = News::find($value['id']);

            if ($value["delete"] === true) {
               $news->delete();
               continue;
            };

            $news->update([
               "hide" => $value['hide'],
            ]);
         };

         $news = News::all();

         // Получение всех файлов
         $filesNews = Storage::files('public/news');
         if ($filesNews) {
            foreach ($filesNews as $fileKey => $fileValue) {
               $useFile = false;

               /* Проверка на использование файла */
               foreach ($news as $key => $value) {
                  if ($value['image'] === basename($fileValue)) {
                     $useFile = true;
                     break;
                  };

                  if (News::where('title', 'like', basename($fileValue)) || News::where('description', 'like', basename($fileValue))) {
                     $useFile = true;
                     break;
                  }
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
            "message" => "Новости изменены.",
            "result" => null,
         ], 200);
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

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     ВИДЕО                         |*/
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
