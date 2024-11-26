<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Slide;
use App\Models\Footer;
use App\Models\Rights;
use App\Models\Status;

class LoginController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     ТОКЕНЫ                        |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Проверки                                          */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   public function checkToken(Request $request) {
      $user = $request->user();

      if (!$user) {
         return response()->json([
            'status' => false,
            'message' => 'Токен недействителен.',
            'data' => null,
         ]);
      }

      return response()->json([
         'status' => true,
         'message' => 'Токен существует.',
         'data' => (object) [
            "nickname" => $user->nickname,
            "email" => $user->email,
            "status" => Status::find($user->statusId)->name,
            "rights" => Rights::find($user->rightsId)->name,
         ],
      ]);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                  ПОЛЬЗОВАТЕЛИ                     |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Основные действия                                 */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Авторизация */
   public function loginUser(Request $request) {
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
         "result" => (object) [
            "token" => $user->createToken('auth_token')->plainTextToken,
         ],
      ]);
   }  

   /* Выход */
   public function logoutUser(Request $request) {
      $request->user()->currentAccessToken()->delete();
      if ($request) {
         return 'Токен удалён.';
      } else {
         return 'Токен не удалён.';
      };
   }

   /* Регистрация */
   public function registerUser(Request $request) {
      dump($request->all());

      // Поиск почты в бд
      $user = User::where('email', $request->email)->first();    
      if ($user) return 'Пользователь с таккой почтой уже есть.';

      $user = User::create([
         'name' => 'user', 
         'email' => 'user@mail.ru', 
         'password' => Hash::make('123456')
      ]);

      dd($user);
   }
}
