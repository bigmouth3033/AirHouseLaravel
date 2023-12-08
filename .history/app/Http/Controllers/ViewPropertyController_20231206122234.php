<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class ViewPropertyController extends Controller
{
    public function readById(Request $request)
    {
        $property = Property::where('id', $request->id)->first();

        foreach ($property->images as $key => $image) {
            $property->images[$key] = asset("storage/images/host/" . $image->image);
        }

        if ($property) {
            return response()->json([
                'data'=>$property
            ]);
        }
    }
}
