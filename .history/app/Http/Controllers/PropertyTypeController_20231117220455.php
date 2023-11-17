<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    //
    public function update(Request $request)
    {
        $id = $request->input("id");
        $request->validate([
            'id' => 'required',
            'name' => 'required|max:50',
            'icon_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $updateAmenity = Amenity::find($id);
        if (!$updateAmenity) {
            return response()->json([
                "success" => false,
                "message" => "ID does not exist. Update unsuccessful!!!",
            ], 404);
        }
        $newFileName = 'images_amenities_' . time() . '_' . $request->file('icon_image')->getClientOriginalName();

        // Store the file in the public disk with the new file name
        $request->file('icon_image')->storeAs('public/images/amenities', $newFileName);
        $updateAmenity->name = $request->input('name');
        $updateAmenity->icon_image = $newFileName;
        $updateAmenity->save();
        return response()->json([
            "success" => true,
            "message" => " Amenity have id : ". $id ." updated successfully.",
            "data" => $updateAmenity,
        ]);
    }
}
