<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\PropertyAmenity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AmenityControllerCopy extends Controller
{
    public function index(){
        $amentities = Amenity::all();
        foreach ($amentities as $amenity){
            if ($amenity->icon_image != null) {
                $amenity->icon_image = asset('storage/images/amenities/' . $amenity->icon_image);
            }else{
            $amenity->icon_image = null;}
        }
        // dd($amentities->toArray());
        return view("Amenity")->with("data", $amentities);
    }
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users|max:50',
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
        return redirect("/");
        }
}
