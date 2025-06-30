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
    /* |                      POST                         |*/
    /* |___________________________________________________|*/
    public function getClientPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => [
                "required",
                Rule::exists("clients", "name"),
            ],
            "snils" => "required",
        ]);

        $clients = Client::all();

        return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => [],
        ], 200);
    }
}
