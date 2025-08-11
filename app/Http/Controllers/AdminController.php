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
use App\Models\User\Rights;
use App\Models\User\Status;
use App\Models\User\User;

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

         // Максимальные значения
         $maxWidth = $request->maxWidth ?? 1500;
         $maxHeight = $request->maxHeight ?? 1500;
         $maxSize = $request->maxSize ?? (5 * 1024);

         // Добавление правил в зависимости от типа
         if ($isImage) {
            $rules['file'] = [
               'required', // общие правила
               File::image()
                  ->types($request->formats)
                  ->dimensions(Rule::dimensions()->maxWidth($maxWidth)->maxHeight($maxHeight)),
               'max:' . $maxSize,
            ];

            $messages['file.required'] = 'Файл не указан.';
            $messages['file.types'] = 'Файл не соответствует форматам.';
            $messages['file.dimensions'] = 'Файл превышает допустимое разрешение ' . $maxWidth . 'px' . $maxHeight . 'px.';
            $messages['file.max'] = 'Файл превышает допустимый размер ' . ($maxSize / 1024) . ' мб.';
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
}
