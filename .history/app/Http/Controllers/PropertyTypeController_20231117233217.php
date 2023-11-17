<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{

    //Không chạy được thì đây sửa table đi pro
    // ALTER TABLE property_type
    // CHANGE `icon-image` `icon_image` VARCHAR(255)
    // CHANGE `updated_at` `updated_at` datetime NOT NULL DEFAULT current_timestamp()


    public function create(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'iconName' => 'required|image|mimes:jpeg,png,jpg,gif,svg', 
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
        $updatePropertyType = PropertyType::find($id);
        if (!$updatePropertyType) {
            return response()->json([
                "success" => false,
                "message" => "ID does not exist. Update unsuccessful!!!",
            ], 404);
        }
        $newFileName = 'images_property_type_' . time() . '_' . $request->file('icon_image')->getClientOriginalName();
        $newFileName_path=asset('storage/images/property_type/' . $newFileName);

        $request->file('icon_image')->storeAs('public/images/property_type', $newFileName);


        $updatePropertyType->name = $request->input('name');
        $updatePropertyType->icon_image = $newFileName_path;
        $updatePropertyType->save();
        return response()->json([
            "success" => true,
            "message" => " Property type have id : ". $id ." updated successfully.",
            "data" => $updatePropertyType,
        ]);
    }
    public function read()
    {
        $PropertyType = PropertyType::all();
        foreach ($PropertyType as $propertyType) {

            if ($propertyType->icon_image != null) {
                $propertyType->icon_image = asset('storage/images/property_type/' . $propertyType->icon_image);
            }
        }
        return response()->json([
            "success" => true,
            "message" => "All property type.",
            "data" => $PropertyType,
        ]);
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $id = $request->input("id");
        if (PropertyType::find($id)) {
            PropertyType::find($id)->delete();
            return response()->json([
                "success" => true,
                "message" => "Deleted propertyt type with ID: " . $id,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "ID does not exist. Deletion unsuccessful!!!"
            ]);
        }
    }
    public function filterByName(Request $request){
        $name = $request->input("name");
        $PropertyTypes = PropertyType::where('name', 'like', '%' . $name . '%')->get();
        if ($PropertyTypes) {
            foreach ($PropertyTypes as $propertyType) {
                if ($propertyType->icon_image !== null) {
                    $propertyType->icon_image = asset('storage/images/property_type/' . $propertyType->icon_image);
                }
                $propertyType->icon_image=null;
            }
            return response()->json([
                "success" => true,
                "message" => "",
                "data" => $PropertyTypes,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Property type not found.",
            ], 404);
        }
    }
}
