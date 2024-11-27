<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Rights;
use App\Models\Status;

class CreatorController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    ПРОВЕРКИ                       |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Права                                             */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Проверка прав пользователя */
   public function chekUserRigths(Request $request) {
      if ($this->isCreator($request)) {
            return response()->json([
               "status" => true,
               "message" => "Всё хорошо.",
               "data" => "creator",
            ]);
      };

      if ($this->isAdmin($request)) {
            return response()->json([
               "status" => true,
               "message" => "Всё хорошо.",
               "data" => "admin",
            ]);
      };

      if ($this->isAdmin($request)) {
            return response()->json([
               "status" => true,
               "message" => "Всё хорошо.",
               "data" => "user",
            ]);
      };
   }
   /* Проверка на создателя */
   public function isCreator(Request $request) {
      $user = $request->user();

      if (Rights::find($user->rightsId)->name === 'creator') {
            return true;
      } else {
            return false;
      };
   }
   /* Проверка на администратора */
   public function isAdmin(Request $request) {
      $user = $request->user();

      if (Rights::find($user->rightsId)->name === 'admin') {
            return true;
      } else {
            return false;
      };
   }
   /* Проверка на обычного пользователя */
   public function isUser(Request $request) {
      $user = $request->user();

      if (Rights::find($user->rightsId)->name === 'user') {
            return true;
      } else {
            return false;
      };
   }
   /* _____________________________________________________*/
   /* 2. Информация о пользователе                         */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Проверка на наличие почты */
   public function isUserEmail($email) {
      if (User::where('email', $email)->first()) {
            return true;
      } else {
            return false;
      };
   }
   /* Проверка на наличие никнейма */
   public function isUserEmailWithout($email, $id) {
      if (User::where('email', $email)->where('id', '!=', $id)->first()) {
            return true;
      } else {
            return false;
      };
   }
   /* Проверка на наличие никнейма */
   public function isUserNickname($nickname) {
      if (User::where('nickname', $nickname)->first()) {
            return true;
      } else {
            return false;
      };
   }
   /* Проверка на наличие никнейма */
   public function isUserNicknameWithout($nickname, $id) {
      if (User::where('nickname', $nickname)->where('id', '!=', $id)->first()) {
            return true;
      } else {
            return false;
      };
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                 ПОЛЬЗОВАТЕЛИ                      |*/
   /* |___________________________________________________|*/
   /* Получение списка пользователей */
   public function getUsersAll(Request $request) {
      if (!$this->isCreator($request)) {
            return response()->json([
               "status" => false,
               "message" => "Недостаточно прав.",
               "data" => [],
            ]);
      };

      $users = User::all();
      
      if(empty($users)) {
            return response()->json([
               "status" => false,
               "message" => "Пользователи не нашлись!",
               "data" => [],
            ]);
      };
      
      foreach ($users as $userKey => $userValue) {
            $rights = Rights::where('id', $userValue->rightsId)->first();
            $status = Status::where('id', $userValue->statusId)->first();

            $userValue->path = Storage::url('users/' . $userValue->filename);
      };

      return response()->json([
            "status" => true,
            "message" => "Успешно!",
            "data" => (object) [
               "users" => $users,
               "rights" => Rights::all(),
               "statuses" => Status::all(),
            ],
      ]);
   }
   /* Сохранение информации о пользователе */
   public function createUser(Request $request) {
      $check = $this->isCreator($request);

      if (!$check) {
            return response()->json([
               "status" => false,
               "message" => "Недостаточно прав.",
               "data" => [],   
            ]);
      } else {
         $userData = json_decode($request->user);

         // Проверка на наличие почты
         if ($this->isUserEmail($userData->email)) {
            return response()->json([
               "status" => false,
               "message" => "Пользователь с такой почтой уже есть.",
               "data" => [],   
            ]);
         };

         // Проверка на наличие никнейма
         if ($this->isUserNickname($userData->nickname)) {
            return response()->json([
               "status" => false,
               "message" => "Пользователь с таким никнеймом уже есть.",
               "data" => [],   
            ]);
         };

         if ($request->hasFile('image')) {
            $validated = validator($request->all(), [
               'image' => [
                  'required',
                  File::types($request->formats)->max(10 * 1024),
               ],
            ]);

            if ($validated->fails()) return response()->json([
               "status" => false,
               "message" => "Файл не прошёл проверку.",
               "data" => null,
            ]);

            $path = $request->file('image')->store(
               'public/users'
            );
         } else {
            return response()->json([
               "status" => false,
               "message" => "Отсутствует изображение.",
               "data" => null,
            ]);
         };

         $user = User::create([
            'family' => $userData->family,
            'name' => $userData->name,
            'surname' => $userData->surname ? $userData->surname : null,
            'nickname' => $userData->nickname,
            'dateOfBirth' => $userData->dateOfBirth,
            'email' => $userData->email,
            'statusId' => $userData->statusId,
            'rightsId' => $userData->rightsId,
            'password' => Hash::make($userData->password),
            'filename' => basename($path),
         ]);

         if(!$user) {
            return response()->json([
               "status" => false,
               "message" => "Не удалось создать нового пользователя...",
               "data" => null,
            ]);
         };
         
         $user->path = Storage::url($path);

         return response()->json([
            "status" => true,
            "message" => "Пользователь создан.",
            "data" => $user,
         ]);
      };
   }
   /* Сохранение информации о пользователе */
   public function saveUser(Request $request) {
      if (!$this->isCreator($request)) {
            return response()->json([
               "status" => false,
               "message" => "Недостаточно прав.",
               "data" => [],   
            ]);
      } else {
         $userData = json_decode($request->user);

         if ($request->hasFile('image')) {
            $validated = validator($request->all(), [
               'image' => [
                  'required',
                  File::types($request->formats)->max(10 * 1024),
               ],
            ]);

            if ($validated->fails()) return response()->json([
               "status" => false,
               "message" => "Файл не прошёл проверку.",
               "data" => null,
            ]);

            $path = $request->file('image')->store(
               'public/users'
            );
         } else {
            $path = null;
         };

         $user = User::find($userData->id);
         // Проверка на наличие никнейма
         if ($this->isUserNicknameWithout($userData->nickname, $user->id)) {
            return response()->json([
               "status" => false,
               "message" => "Пользователь с таким никнеймом уже есть.",
               "data" => [],   
            ]);
         };

         // Проверка на наличие почты
         if ($this->isUserEmailWithout($userData->email, $user->id)) {
            return response()->json([
               "status" => false,
               "message" => "Пользователь с такой почтой уже есть.",
               "data" => [],   
            ]);
         };

         if ($user->rightsId !== $userData->rightsId) {
            $users = User::where('rightsId', $user->rightsId)->get();

            if ((count($users) - 1) < 1) {
               return response()->json([
                  "status" => false,
                  "message" => "В системе должен быть хотя бы 1 создатель.",
                  "data" => [],   
               ]);
            }
         }        

         $user->family = $userData->family;
         $user->name = $userData->name;
         $user->surname = $userData->surname;
         $user->dateOfBirth = $userData->dateOfBirth;
         $user->nickname = $userData->nickname;
         $user->email = $userData->email;
         $user->statusId = $userData->statusId;
         $user->rightsId = $userData->rightsId;
         if ($path) $user->filename = basename($path);
         if (!empty($userData->password)) $user->password = Hash::make($userData->password);
         $user->save();            
         
         return response()->json([
            "status" => true,
            "message" => "Данные обновлены.",
            "data" => (object) [
               "path" => $path ? Storage::url($path) : null,
            ],
         ]);
      };
   }
   /* Установка нового пароля */
   public function deleteUser(Request $request) {
      $userId = json_decode($request->userId);

      $user = User::find($userId);

      if (!$user) {
         return response()->json([
            "status" => false,
            "message" => "Пользователь не найден.",
            "data" => [],
         ]);
      };

      $user->delete();

      return response()->json([
         "status" => true,
         "message" => "Пользователь удалён.",
         "data" => [],
      ]);
   }
   /* Установка нового пароля */
   public function setUserPassword(Request $request) {
      $check = $this->isCreator($request);

      if (!$check) {
            return response()->json([
               "status" => false,
               "message" => "Недостаточно прав.",
               "data" => [],   
            ]);
      } else {        
         $password = json_decode($request->password);
         $userId = json_decode($request->userId);
         $user = User::find($userId);

         if (!$user) {
            return response()->json([
               "status" => false,
               "message" => "Пользователь не нашелся.",
               "data" => [],   
            ]);
         };

         $user->password = Hash::make($password);
         $user->save();

         return response()->json([
            "status" => true,
            "message" => "Данные обновлены.",
            "data" => [],
         ]);
      };
   }
   /* Установка нового статуса */
   public function setUserStatus(Request $request) {
      $check = $this->isCreator($request);

      if (!$check) {
            return response()->json([
               "status" => false,
               "message" => "Недостаточно прав.",
               "data" => [],   
            ]);
      } else {        
         $userId = json_decode($request->userId);
         $user = User::find($userId);
         
         if (!$user) {
            return response()->json([
               "status" => false,
               "message" => "Пользователь не нашелся.",
               "data" => [],   
            ]);
         };

         $user->statusId = $user->statusId === 1 ? 2 : 1;
         $user->save();

         return response()->json([
            "status" => true,
            "message" => "Данные обновлены.",
            "data" => $user->statusId,
         ]);
      };
   }    
}
