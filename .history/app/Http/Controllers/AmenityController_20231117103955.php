<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    //
    public function create(Request $request){
        $validateData = $request->validate([
             'name' => 'required|max:50'
        ]);
    }
}
