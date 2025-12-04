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

class LoginController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    ПРОВЕРКИ                       |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Пользователя                                      */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   public function checkUser(Request $request) {
      if ($this->isUserTokenNull($request)) {
         return response()->json([
            "status" => false,
            "message" => "Токен недействителен.",
            "data" => null,
         ]);
      };

      if ($this->isUserStatusPassive($request)) {
         return response()->json([
            "status" => false,
            "message" => "Пользователь заблокирован.",
            "data" => null,
         ]);
      };

      $user = $request->user();
      return response()->json([
         'status' => true,
         'message' => 'Проверка пройдена.',
         'data' => (object) [
            "nickname" => $user->nickname,
            "email" => $user->email,
            "status" => Status::find($user->statusId)->name,
            "rights" => Rights::find($user->rightsId)->name,
            "image" => Storage::url('users/' . $user->filename),
         ],
      ]);
   }
   public function isUserStatusPassive(Request $request) {
      $user = $request->user();

      if (Status::find($user->statusId)->name === 'passive') {
         return true;
      } else {
         return false;
      };   
   }
   public function isUserTokenNull(Request $request) {
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
   public function login(Request $request) {
      // Валидация данных
      $validated = validator($request->all(), [
         "name" => "required",
         "password" => "required"      
      ]);
      if ($validated->fails()) return response()->json([
         "status" => false,
         "message" => "Есть ошибки.",
         "token" => "",
      ]);

      // Поиск пользователя по логину в бд
      $user = User::where('nickname', $request->name)->first();    
      if (!$user) return response()->json([
         "status" => false,
         "message" => "Пользователь не найден.",
         "token" => '',
      ]);

      // Проверка хешированного пароля
      $hashPass = Hash::check($request->password, $user->password);      
      if (!$hashPass) return response()->json([
         "status" => false,
         "message" => "Неверный логин или пароль.",
         "token" => "",
      ]);

      if (Status::find($user->statusId)->name == 'passive' ) return response()->json([
         "status" => false,
         "message" => "Пользователь заблокирован.",
         "data" => [],
      ]);

      $user->tokens()->delete();
      return response()->json([
         "status" => true,
         "message" => "Авторизация пройдена.",
         "data" => (object) [
            "token" => $user->createToken('auth_token')->plainTextToken,
         ],
      ]);
   }  

   /* Выход из системы */
   public function logout(Request $request){
      $request->user()->currentAccessToken()->delete();

      return response()->json([
         "success" => true,
         "message" => 'Выход из системы.',
         "data" => null,
      ]);
   }
}
