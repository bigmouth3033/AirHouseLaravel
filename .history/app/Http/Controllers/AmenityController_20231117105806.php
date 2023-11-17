<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    //
    public function create(Request $request){
        $validatedData = $request->validate([
             'name' => 'required|max:50',
             'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust validation rules for the image
        ]);
        $Amenity = new Amenity;
        $Amenity->name = $validatedData['name'];
        //Lưu hình ảnh vào public
        $imagePath = $request->file('icon')->store('public/images/amenties');
        //Trích xuất tên tệp thực tế từ đường dẫn được lưu trữ
        $fileNameImage = basename($imagePath);
        $Amenity->icon_image= $fileNameImage
        $Amenity->save();
        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $Amenity 
        ]);
    }
}
