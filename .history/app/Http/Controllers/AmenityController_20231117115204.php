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
        $Amenity->name = $validatedData(['name']);
        $originalFileName = $request->file('icon')->getClientOriginalName();
    
        // Tạo tên mới theo mẫu "images_amenities_originalFileName"
        $newFileName = 'images_amenities_' . $originalFileName;
    
        // Lưu hình ảnh vào thư mục public/images/amenities với tên mới và phần mở rộng
        $imagePath = $request->file('icon')->storeAs('images/amenities', $newFileName, 'public');
    
        // Lưu đường dẫn và tên tệp vào cơ sở dữ liệu
        $Amenity->icon_image = $imagePath;
    
        $Amenity->save();
        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $Amenity 
        ]);
    }
}
