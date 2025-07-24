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
use App\Models\News;

class NewsController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   /* Получение всех новостей */
   public function getNewsAll(Request $request)
   {
      try {
         $news = News::all()->sortByDesc('created_at')->values()->all();

         foreach ($news as $key => $value) {
            $value->date = $value->created_at;
            $value->url_date = Carbon::parse($value->created_at)->format('d.m.Y');
            $value->url_time = Carbon::parse($value->created_at)->format('H:i:s');
            $value->path = Storage::url('news/' . $value->image);
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $news,
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

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => [
               "news" => $news,
               "count" => News::all()->where('hide', 1)->count(),
            ],
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
   /* |                      POST                         |*/
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
}
