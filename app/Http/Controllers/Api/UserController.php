<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class UserController extends Controller
{
    public function signup(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users|max:50',
            'password' => [
                'required', 'confirmed', Password::min(8)->letters()->symbols()->numbers(),
            ],
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'birthday' => 'required|date'
        ]);

        $user = new User();
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->date_of_birth = $validatedData['birthday'];
        $user->first_name = $validatedData['first_name'];
        $user->last_name  = $validatedData['last_name'];
        $user->save();

        $token = $user->createToken('myToken')->plainTextToken;
        return response(compact('user', 'token'));
    }

    public function signupAdmin(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users|max:50',
            'password' => [
                'required', 'confirmed', Password::min(8)->letters()->symbols()->numbers(),
            ],
        ]);

        $user = new User();
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->date_of_birth = "1999-01-01";
        $user->first_name = "admin";
        $user->last_name  = "admin";
        $user->user_type = 0;
        $user->save();

        return response(compact('user'));
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|max:50',
            'password' => [
                'required', Password::min(8)->letters()->symbols()->numbers(),
            ],
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response([
                'message' => 'wrong fucking account or password'
            ], 401);
        }

        $token = $user->createToken('myToken')->plainTextToken;
        return response(compact('user', 'token'));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'logout'
        ]);
    }
}
