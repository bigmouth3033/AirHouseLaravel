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
             'iconName' => 'image|mimes:jpeg,png,jpg,gif,svg', // Adjust validation rules for the image
        ]);
        $Amenity = new Amenity;
        $Amenity->name = $validatedData['name'];
        //Lay ten cu
        $originalFileName = $request->file('iconName')->getClientOriginalName();
        //tao ten moi
        $newFileName = 'images/amenities/' . $originalFileName;
        //Lưu hình ảnh vào thư mục public/images/amenities với tên mới
        $request->file('icoName')->storeAs('public/image/amenities', $newFileName);
        //Luu vao truowng icon trong table
        $Amenity->icon_image= $newFileName ;
        $Amenity->save();
        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $Amenity 
        ]);
    }
    public function read(){

    }
}
