<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewPropertyController extends Controller
{
    public function read(){
        $property = Property::where('id',$request->id)->first();

        if ($property) {
            return response()->json([
                'data' => $property,
            ]);
        } else {
            return response()->json([
                'error' => 'Property not found',
            ], 404);
        }
    }
}

