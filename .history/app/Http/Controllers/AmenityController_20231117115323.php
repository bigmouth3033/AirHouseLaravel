<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    //php artisan storage:link
    public function create(Request $request){
        $validatedData = $request->validate([
             'name' => 'required|max:50',
             'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust validation rules for the image
        ]);
        $Amenity = new Amenity;
        $Amenity->name = $validatedData['name'];
        //Lay ten cu
        $originalFileName = $request->file('icon')->getClientOriginalName();
        //tao ten moi
        $newFileName = 'images_amenities_' . $originalFileName;
        //Lưu hình ảnh vào thư mục public/images/amenities với tên mới
        $imagePath = $request->file('icon')->storeAs('images/amenities', $newFileName);
        //Luu vao truowng icon trong table
        $Amenity->icon_image= $imagePath;
        $Amenity->save();
        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $Amenity 
        ]);
    }
}
