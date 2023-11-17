<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{

    //Không chạy được thì đây sửa table đi pro
    // ALTER TABLE property_type
    // CHANGE `updated_at` `updated_at` datetime NOT NULL DEFAULT current_timestamp()
    // CHANGE `icon-image` `icon_image` VARCHAR(255)


    public function create(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'iconName' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust validation rules for the image
        ]);

        $PropertyType = new PropertyType;
        $PropertyType->name = $validatedData['name'];

        $newFileName = 'images_property_type_' . time() . '_' . $request->file('iconName')->getClientOriginalName();

        $request->file('iconName')->storeAs('public/images/property_type', $newFileName);

        $PropertyType->icon_image = $newFileName;
        $PropertyType->save();

        return response()->json([
            "success" => true,
            "message" => "A property type created successfully.",
            "data" => $PropertyType,
        ]);
    }
    public function update(Request $request)
    {
        $id = $request->input("id");
        $request->validate([
            'id' => 'required',
            'name' => 'required|max:50',
            'icon_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $updateAmenity = PropertyType::find($id);
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
