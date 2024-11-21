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
    public function saveUser(Request $request) {
        $check = $this->isCreator($request);

        if (!$check) {
            return response()->json([
                "status" => false,
                "message" => "Недостаточно прав.",
                "data" => [],   
            ]);
        } else {
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

            $userData = json_decode($request->user);
            $user = User::find($userData->id);
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
}
