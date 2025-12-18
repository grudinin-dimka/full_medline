<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User\User;
use App\Models\User\Rights;
use App\Models\User\Status;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Illuminate\Support\Facades\Storage;

use Throwable;

class LoginController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    ПРОВЕРКИ                       |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Пользователя                                      */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   public function checkUser(Request $request)
   {
      if ($this->isUserTokenNull($request)) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Токен недействителен.",
            "result" => null,
         ], 401);
      };

      if ($this->isUserStatusPassive($request)) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Пользователь заблокирован.",
            "result" => null,
         ], 401);
      };

      $user = $request->user();

      return response()->json([
         'success' => true,
         'debug' => false,
         'message' => 'Проверка пройдена.',
         'result' => [
            "nickname" => $user->nickname,
            "email" => $user->email,
            "status" => Status::find($user->statusId)->name,
            "rights" => Rights::find($user->rightsId)->name,
            "image" => Storage::url('users/' . $user->filename),
         ],
      ], 200);
   }
   public function isUserStatusPassive(Request $request)
   {
      $user = $request->user();

      if (Status::find($user->statusId)->name === 'passive') {
         return true;
      } else {
         return false;
      };
   }
   public function isUserTokenNull(Request $request)
   {
      $user = $request->user();

      if (!$user) {
         return true;
      } else {
         return false;
      }
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                  ПОЛЬЗОВАТЕЛИ                     |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Основные действия                                 */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Авторизация */
   public function login(Request $request)
   {
      // Валидация данных
      $validated = validator($request->all(), [
         "name" => "required",
         "password" => "required"
      ], [
         "name.required" => "Поле логин обязательно.",
         "password.required" => "Поле пароль обязательно.",
      ]);

      if ($validated->fails()) return response()->json([
         "success" => false,
         "debug" => true,
         "errors" => $validated->errors(),
         "message" => "Есть ошибки.",
         "result" => null,
      ], 422);

      // Поиск пользователя по логину в бд
      $user = User::where('nickname', $request->name)->first();

      if (!$user) return response()->json([
         "success" => false,
         "debug" => true,
         "message" => "Пользователь не найден.",
         "result" => '',
      ], 422);

      // Проверка хешированного пароля
      $hashPass = Hash::check($request->password, $user->password);

      if (!$hashPass) return response()->json([
         "success" => false,
         "debug" => true,
         "message" => "Неверный логин или пароль.",
         "result" => "",
      ], 422);

      if (Status::find($user->statusId)->name == 'passive') return response()->json([
         "success" => false,
         "debug" => true,
         "message" => "Пользователь заблокирован.",
         "result" => null,
      ], 422);

      // Удаление старых токенов
      $user->tokens()->delete();

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Авторизация пройдена.",
         "result" => $user->createToken('auth_token')->plainTextToken,
      ], 200);
   }

   /* Выход из системы */
   public function logout(Request $request)
   {
      try {
         $request->user()->currentAccessToken()->delete();

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => 'Выход из системы.',
            "result" => null,
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
