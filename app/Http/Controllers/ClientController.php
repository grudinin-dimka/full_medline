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

/* Модели */
use App\Models\Client;

class ClientController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   public function getClients(Request $request)
   {
      try {
         $clients = Client::all();

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $clients,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Произошла ошибка.",
            "result" => [],
         ], 500);
      };
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      POST                         |*/
   /* |___________________________________________________|*/
   public function getClientPoints(Request $request)
   {
      $validator = Validator::make($request->all(), [
         "name" => [
            "required",
            Rule::exists("clients", "name"),
         ],
         "snils" => [
            "required",
            Rule::exists("clients", "snils"),
         ],
      ], [
         "name.required" => "Поле ФИО обязательно.",
         "name.exists" => "Клиент с таким ФИО не зарегистрирован.",
         "snils.required" => "Поле СНИЛС обязательно.",
         "snils.exists" => "Клиент с таким СНИЛС не зарегистрирован.",
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Ошибка в данных для запроса.",
            "result" => [],
         ], 422);
      };

      try {
         $client = Client::where("name", $request->name)->where("snils", $request->snils)->first();

         if (!$client) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Клиент не найден.",
               "result" => [],
            ], 422);
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => [
               "points" => $client->points,
               "barcode" => $client->barcode
            ],
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Произошла ошибка.",
            "result" => [],
         ], 500);
      }
   }

   /* Сохранение поинтов из файла */
   public function saveClientPoints(Request $request)
   {
      try {
         // Проверка наличия директории
         if (!Storage::disk('private')->exists('clients')) {
            Log::error("Директория clients не найдена.");

            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Дериктория clients не найдена.",
               "result" => [],
            ], 500);
         };

         // Получение данных из файла
         $jsonClients = Storage::disk('private')->get('clients/clients.json');

         if (!$jsonClients) {
            Log::error("Файл clients.json не найден.");

            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Файл clients.json не найден.",
               "result" => [],
            ], 500);
         };

         $clients = json_decode($jsonClients);

         foreach ($clients as $client) {
            $clientFromDB = Client::where("name", $client->name)->where("snils", $client->snils)->first();

            if (!$clientFromDB) {
               Client::create([
                  "name" => $client->name,
                  "snils" => $client->snils,
                  "points" => $client->points,
                  "barcode" => $client->barcode,
               ]);
            } else {
               $clientFromDB->points = $client->points;
               $clientFromDB->barcode = $client->barcode;

               $clientFromDB->save();
            };
         };

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Данные сохранены.",
            "result" => Client::all(),
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => [],
         ], 500);
      };
   }
}
