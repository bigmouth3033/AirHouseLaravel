<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Ramsey\Uuid\Uuid;


class UserController extends Controller
{
    public function signup(Request $request)
    {
        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->date_of_birth = $request->birthday;
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->save();

        $token = $user->createToken('myToken')->plainTextToken;
        return response(compact('user', 'token'));
    }

    public function signupAdmin(Request $request)
    {
        $user = new User();
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->date_of_birth = "1999-01-01";
        $user->first_name = "admin";
        $user->last_name  = "admin";
        $user->user_type = 0;
        $user->save();

        return response(compact('user'));
    }

    public function login(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'wrong account or password'
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

    public function readById($id)
    {
        $user = User::find($id);

        if ($user) {
            return response($user);
        }

        return response(['message' => 'not found']);
    }

    public function updateUser(Request $request)
    {
        $email = $request->email;
        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $phoneNumber = $request->phoneNumber;
        $gender = $request->gender;
        $address = $request->address;
        $about = $request->about;

        $user = DB::table('users')->where('email', $email)->update([
            'first_name' => $firstName,
            'last_Name' => $lastName,
            'phonenumber' => $phoneNumber,
            'gender' => $gender,
            'address' => $address,
            'about' => $about
        ]);

        $data = [$email, $firstName, $lastName, $phoneNumber, $gender, $address, $about];
        return $user;
    }

    public function uploadImage(Request $request)
    {

        $user = $request->user();
        $originFileName = $request->file('image')->getClientOriginalName();

        $newFileName = 'images_amenities_' . Uuid::uuid4()->toString() . '_' . $originFileName;
        $request->file('image')->storeAs('public/images/users', $newFileName);
        $newFileName_path = asset('storage/images/users/' . $newFileName);
        $user->image = $newFileName_path;
        $user->email = $request->email;
        $user->save();


        return $newFileName_path;
    }

    public function checkEmailUnique(Request $request)
    {
        $email = User::where('email', $request->email)->first();

        if ($email) {
            return response([
                'message' => 'email already exist'
            ], 406);
        }

        return response([
            'message' => "acceptable"
        ]);
    }


    public function signUpGoogle(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token =  $user->createToken('myToken')->plainTextToken;
            return response([
                'user' => $user,
                'token' => $token
            ]);
        }

        return response(['message' => 'cant do that'], 403);
    }
}
