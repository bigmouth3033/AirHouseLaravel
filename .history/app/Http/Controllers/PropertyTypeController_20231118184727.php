<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

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
            'icon_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg', 
        ]);
        
        $propertyType = new PropertyType;
        
        $originFileName = $request->file('icon_image')->getClientOriginalName();
        $newFileName = 'images_property_type_' . Uuid::uuid4()->toString() . '_' . $originFileName;
        
        $request->file('icon_image')->storeAs('public/images/property_type', $newFileName);
        
        $propertyType->icon_image = $newFileName;
        $propertyType->name = $validatedData['name'];
        $propertyType->save();
        
        $newFileName_path=asset('storage/images/property_type/' . $newFileName);
        $propertyType->icon_image= $newFileName_path;
        return response()->json([
            "success" => true,
            "message" => "A property type created successfully.",
            "data" => $propertyType,
        ],201);
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
       $originFileName = $request->file('icon_image')->getClientOriginalName();
        $newFileName = 'images_property_type_' . Uuid::uuid4()->toString() . '_' . $originFileName;
        
        $request->file('icon_image')->storeAs('public/images/property_type', $newFileName);
        
        $updatePropertyType->name = $request->input('name');
        $updatePropertyType->icon_image = $newFileName;
        $updatePropertyType->save();
        
        $newFileName_path=asset('storage/images/property_type/' . $newFileName);
        $updatePropertyType->icon_image= $newFileName_path;
        return response()->json([
            "success" => true,
            "message" => " Property type have id : ". $id ." updated successfully.",
            "data" => $updatePropertyType,
        ],200);
    }
    public function read()
    {
        $propertyType = PropertyType::all();
        foreach ($propertyType as $propertyType) {

            if ($propertyType->icon_image != null) {
                $propertyType->icon_image = asset('storage/images/property_type/' . $propertyType->icon_image);
            }else{
                $propertyType->icon_image =null;
            }
        }
        return response()->json([
            "success" => true,
            "message" => "All property type.",
            "data" => $propertyType,
        ],200);
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $id = $request->input("id");
        $propertyType = PropertyType::find($id);
        if ($propertyType) {
            $propertyType->delete();
            return response()->json([
                "success" => true,
                "message" => "Deleted propertyt type with ID: " . $id,
            ],200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "ID does not exist. Deletion unsuccessful!!!"
            ],404);
        }
    }
    public function filterByName(Request $request){
        $name = $request->input("name");
        $PropertyTypes = PropertyType::where('name', 'like', '%' . $name . '%')->get();
        if (count($PropertyTypes) > 0) {
            foreach ($PropertyTypes as $propertyType) {
                if ($propertyType->icon_image != null) {
                    $propertyType->icon_image = asset('storage/images/property_type/' . $propertyType->icon_image);
                }else{
                    $propertyType->icon_image=null;
                }
                
            }
            return response()->json([
                "success" => true,
                "message" => "All of property type list",
                "data" => $PropertyTypes,
            ],200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Property type not found.",
            ], 404);
        }
    }
}
