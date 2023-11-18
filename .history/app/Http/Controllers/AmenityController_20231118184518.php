<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class AmenityController extends Controller
{
    //php artisan storage:link
    // composer require ramsey/uuid to import library
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'icon_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg', 
        ]);

        $amenity = new Amenity;
        $originFileName = $request->file('icon_image')->getClientOriginalName();
        $newFileName = 'images_amenities_' . Uuid::uuid4()->toString() . '_' . $originFileName;
        
        $request->file('icon_image')->storeAs('public/images/amenities', $newFileName);
        
        $amenity->name = $validatedData['name'];
        $amenity->icon_image = $newFileName;
        $amenity->save();
        //tra du lieu ve
        $newFileName_path = asset('storage/images/amenities/' . $newFileName);
        $amenity->icon_image= $newFileName_path;
        return response()->json([
            "success" => true,
            "message" => "A amenity created successfully.",
            "data" => $amenity,
        ]);
    }

    public function read()
    {
        $Amenities = Amenity::all();

        foreach ($Amenities as $amenity) {
            if ($amenity->icon_image !== null) {
                $amenity->icon_image = asset('storage/images/amenities/' . $amenity->icon_image);
            }else{
                $amenity->icon_image =null;
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
        $originFileName = $request->file('icon_image')->getClientOriginalName();
        $newFileName = 'images_amenities_' . Uuid::uuid4()->toString() . '_' . $originFileName;
        $request->file('icon_image')->storeAs('public/images/amenities', $newFileName);
        
        $updateAmenity->name = $request->input('name');
        $updateAmenity->icon_image = $newFileName;
        
        $updateAmenity->save();
        
        $newFileName_path = asset('storage/images/amenities/' . $updateAmenity->icon_image);
        $updateAmenity->icon_image = $newFileName_path;
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
        $amenities = Amenity::find($id);
        if ($amenities) {
            $amenities->delete();
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
        // Để kiểm tra xem Collection có rỗng hay không, sử dụng phương thức isEmpty() hoặc count()
        if (count($Amenities) > 0) {
            foreach ($Amenities as $amenity) {
                if ($amenity->icon_image != "") {
                    $amenity->icon_image = asset('storage/images/amenities/' . $amenity->icon_image);
                } else {
                    $amenity->icon_image = null;
                }
            }
            return response()->json([
                "success" => true,
                "message" => "All of amenities list",
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
