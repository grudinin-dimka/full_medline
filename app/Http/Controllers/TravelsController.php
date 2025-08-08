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

class TravelsController extends Controller
{
    /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
    /* |                      GET                          |*/
    /* |___________________________________________________|*/
    /* Получение всех новостей */
    public function getTravelsAll(Request $request)
    {
        try {
            $travels = Travels::all();

            foreach ($travels as $key => $value) {
                $value->path = Storage::url('travels/' . $value->image);
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

    /* Получение одной новости */
    public function getTravelsOnce(Request $request)
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
            $news = Travels::whereDate('created_at', Carbon::parse($request->date))->whereTime('created_at', '=', $request->time)->first();
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

    /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
    /* |                      POST                         |*/
    /* |___________________________________________________|*/
    /* Добавление */
    public function addTravels(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'image' => [
        //         'required',
        //         File::image()
        //             ->types(['png', 'webp', 'jpg', 'jpeg'])
        //             ->max(5 * 1024)
        //             ->dimensions(Rule::dimensions()->maxWidth(2000)->maxHeight(2000)),
        //     ],
        //     'title' => 'required|string',
        //     'description' => 'required|string',
        // ], [
        //     'required' => 'Поле :attribute обязательно для заполнения.',
        //     'string' => 'Поле :attribute должно быть строкой.',
        //     'image.types' => 'Допустимые типы файлов: png, webp, jpg, jpeg.',
        //     'image.max' => 'Максимальный размер 5MB.',
        //     'image.dimensions' => 'Максимальное разрешение 2000x2000px.',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         "success" => false,
        //         "debug" => true,
        //         "errors" => $validator->errors(),
        //         "message" => "Некорректные данные.",
        //         "result" => null,
        //     ], 422);
        // };

        // $path = request()->file('image')->store(
        //     'public/news'
        // );

        // if (!$path) {
        //     return response()->json([
        //         "success" => false,
        //         "debug" => true,
        //         "message" => "Не удалось сохранить изображение.",
        //         "result" => null,
        //     ], 500);
        // };

        // try {
        //     $news = News::create([
        //         'image' => basename($path),
        //         'title' => $request->title,
        //         'description' => $request->description,
        //     ]);
        // } catch (Throwable $th) {
        //     return response()->json([
        //         "success" => false,
        //         "debug" => true,
        //         "message" => "Не удалось добавить новость.",
        //         "result" => null,
        //     ], 500);
        // }

        // return response()->json([
        //     "success" => true,
        //     "debug" => true,
        //     "message" => "Новость добавлена.",
        //     "result" => [
        //         "id" => $news->id,
        //         "path" => Storage::url($path),
        //         "image" => basename($path),
        //         "title" => $request->title,
        //         "description" => $request->description,
        //         "url_date" => Carbon::parse($news->created_at)->format('d.m.Y'),
        //         "url_time" => Carbon::parse($news->created_at)->format('H:i:s'),
        //     ],
        // ], 200);
    }
}
