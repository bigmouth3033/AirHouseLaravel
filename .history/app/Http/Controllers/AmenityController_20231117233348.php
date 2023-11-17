<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    //php artisan storage:link

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'iconName' => 'required|image|mimes:jpeg,png,jpg,gif,svg', 
        ]);

        $Amenity = new Amenity;
        $Amenity->name = $validatedData['name'];

        $newFileName = 'images_amenities_' . time() . '_' . $request->file('iconName')->getClientOriginalName();
        $request->file('iconName')->storeAs('public/images/amenities', $newFileName);
        $Amenity->icon_image = $newFileName;

        $Amenity->save();

        return response()->json([
            "success" => true,
            "message" => "A amenity created successfully.",
            "data" => $Amenity,
        ]);
    }

    public function read()
    {
        $Amenities = Amenity::all();

        foreach ($Amenities as $amenity) {
            if ($amenity->icon_image !== null) {
                $amenity->icon_image = asset('storage/images/amenities/' . $amenity->icon_image);
            }
        }
        return response()->json([
            "success" => true,
            "message" => "All amenity.",
            "data" => $Amenities,
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
        $updateAmenity = Amenity::find($id);
        if (!$updateAmenity) {
            return response()->json([
                "success" => false,
                "message" => "ID does not exist. Update unsuccessful!!!",
            ], 404);
        }
        $newFileName = 'images_amenities_' . time() . '_' . $request->file('icon_image')->getClientOriginalName();
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
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $id = $request->input("id");
        if (Amenity::find($id)) {
            Amenity::find($id)->delete();
            return response()->json([
                "success" => true,
                "message" => "Deleted amenity with ID: " . $id,
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
        $Amenities = Amenity::where('name', 'like', '%' . $name . '%')->get();

        if ($Amenities) {
            foreach ($Amenities as $amenity) {
                if ($amenity->icon_image != "") {
                    $amenity->icon_image = asset('storage/images/amenities/' . $amenity->icon_image);
                }else{
                    $amenity->icon_image=null;
                }
            }
            return response()->json([
                "success" => true,
                "message" => "",
                "data" => $Amenities,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Amenity not found.",
            ], 404);
        }
    }
}
