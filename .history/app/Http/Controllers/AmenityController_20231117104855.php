<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    //
    public function create(Request $request){
        $validatedData = $request->validate([
             'name' => 'required|max:50'
        ]);
        $Amenity = new Amenity;
        $Amenity->name = $validatedData['name'];
        $Amenity->icon_image= $request->icon;
        $Amenity->save();
        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $Amenity 
        ]);
    }
}
