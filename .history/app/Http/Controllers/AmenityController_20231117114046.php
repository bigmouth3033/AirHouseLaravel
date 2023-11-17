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
        //lay ten cu
        $originalFileName = $request->file('icon')->getClientOriginalName();
        //tao ten moi
        $newFileName = 'images_amenities_' . $originalFileName;
        $imagePath = $request->file('icon')->storeAs('images/amenities', $request->file('icon')->getClientOriginalName(), 'public');
        //Trích xuất tên tệp thực tế từ đường dẫn được lưu trữ
        //basename($imagePath) sẽ trả về filename
        $fileNameImage = basename($imagePath);
        $Amenity->icon_image= $fileNameImage;
        $Amenity->save();
        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $Amenity 
        ]);
    }
}
