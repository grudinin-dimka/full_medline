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
    public function getUsersAll(Request $request) {
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
}
