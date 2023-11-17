<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AmenityController extends Controller
{
    //php artisan storage:link
    // Lệnh này sẽ tạo một liên kết từ thư mục public đến thư mục storage/app/public, giúp bạn có thể truy cập các tệp trong thư mục storage/app/public từ URL trong ứng dụng Laravel của bạn.


    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'iconName' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust validation rules for the image
        ]);
    
        $Amenity = new Amenity;
        $Amenity->name = $validatedData['name'];
    
         // Generate a new file name
    $newFileName = 'images_amenities_' . time() . '_' . $request->file('iconName')->getClientOriginalName();

    // Store the file in the public disk with the new file name
    $storedImagePath = $request->file('iconName')->storeAs('public/images/amenities', $newFileName);

    // Save the internal path in the 'icon_image' field of the Amenity model
    $amenity->icon_image = 'images/amenities/' . $newFileName;
    $amenity->save();
    
        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $Amenity,
        ]);
    }
    
    public function store(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'iconName' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust validation rules for the image
        ]);
    
        $Amenity = new Amenity;
        $Amenity->name = $validatedData['name'];

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
