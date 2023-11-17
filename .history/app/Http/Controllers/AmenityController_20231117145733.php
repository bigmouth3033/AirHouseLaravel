<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AmenityController extends Controller
{
    //php artisan storage:link
    // Lệnh này sẽ tạo một liên kết từ thư mục public đến thư mục storage/app/public, giúp bạn có thể truy cập các tệp trong thư mục storage/app/public từ URL trong ứng dụng Laravel của bạn.
    public function create(Request $request){
        $validatedData = $request->validate([
             'name' => 'required|max:50',
             'iconName' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust validation rules for the image
        ]);
        $Amenity = new Amenity;
        $Amenity->name = $validatedData['name'];

        $newFileName = "images/amenities/" . $request->file('iconName')->getClientOriginalName();
        $newFileNamePubllic = "images_amenities_" . $request->file('iconName')->getClientOriginalName();

        // Lưu tệp vào thư mục public/images/amenities
        $request->file('iconName')->move(public_path('images/amenities'), $newFileNamePubllic);
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
        $amenities = Amenity::all();

        // Thêm URL của hình ảnh vào mỗi đối tượng Amenities
        $amenitiesWithUrls = $amenities->map(function ($amenity) {
            // Kiểm tra xem có tệp tin ảnh hay không trước khi thêm URL
            if (Storage::disk('public')->exists($amenity->icon_image)) {
                $amenity->image_url = Storage::url($amenity->icon_image);
            } else {
                $amenity->image_url = null;
            }
            return $amenity;
        });
    
        return response()->json([
            "success" => true,
            "message" => "",
            "data" => $amenitiesWithUrls,
        ]);
    }
    public function show(Request $request, $id){
        $Amentity = Amenity::find($id);
        if($Amentity){

        }

    }  
}
