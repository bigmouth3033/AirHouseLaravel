<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $user = new User();
        $user->first_name = "a";
        $user->last_name = "b";
        $user->email = "a@gmail.com";
        $user->password = bcrypt("123456");
        $user->date_of_birth = "1997-02-05";

        $user->save();
        $token = $user->createToken('main')->plainTextToken;
        return response(compact('user', 'token'));
    }
}
