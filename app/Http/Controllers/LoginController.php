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

class LoginController extends Controller
{
  public function index(Request $request) {
    return 'Успешно';
  }

  public function checkToken(Request $request) {
    if (!($request->user())) {
      return response()->json([
        'status' => false,
      ]);
    }
  }

  /*-----------------------------------------*/
  /*--------Автороизация пользователя--------*/
  /*-----------------------------------------*/
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
    $user = User::where('name', $request->name)->first();    
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

    $user->tokens()->delete();
    return response()->json([
      "status" => true,
      "message" => "Авторизация пройдена.",
      "token" => $user->createToken('auth_token')->plainTextToken,
      "data" => (object) [
        "token" => $user->createToken('auth_token')->plainTextToken,
        "user" => (object) [
          "id" => $user->id,
          "name" => $user->name,
          "email" => $user->email
        ],
      ],
    ]);
  }  
  
  /*-----------------------------------------*/
  /*------------Выход из аккаунта------------*/
  /*-----------------------------------------*/
  public function logoutUser(Request $request) {
    $request->user()->currentAccessToken()->delete();
    if ($request) {
      return 'Токен удалён.';
    } else {
      return 'Токен не удалён.';
    };
  }

  /*-----------------------------------------*/
  /*--------Регистрация пользователя---------*/
  /*-----------------------------------------*/
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
