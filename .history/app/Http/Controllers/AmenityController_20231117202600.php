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
        $request->file('iconName')->storeAs('public/images/amenities', $newFileName);

        // Save the internal path in the 'icon_image' field of the Amenity model
        $Amenity->icon_image = $newFileName;
        $Amenity->save();

        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $Amenity,
        ]);
    }

    public function getImage(Request $request)
    {
        // Assume $filename is the filename stored in your database column
        $filename = $request->input('filename');

        // Create the full URL for the image using Storage::url()
        // $imageUrl = Storage::url('public/images/amenities/' . $filename);
        $imageUrl = asset('storage/images/amenities/' . $filename);

        return response()->json([
            "success" => true,
            "message" => "",
            "data" => $imageUrl,
        ]);
    }
    public function read()
    {
        $Amenities = Amenity::all();
        for ($i = 0; $i < count($Amenities); $i++) {
            $url_origin= $Amenities[$i]->get('icon_image');
            $url_new= asset('storage/images/amenities/' . $url_origin);
            $Amenities[$i]->icon_image= $url_new;
        }
        
       
        return response()->json([
            "success" => true,
            "message" => "",
            // "data" => $amenitiesWithUrls,
            "data"=> $Amenities ,
            // "data"=> $url_origin,
        ]);
    }
    public function update(Request $request)
    {
        $id = $request->input("id");
        $request->validate([
            'id'=>'required',
            'name' => 'required|max:50',
            'icon_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $updateAmenity = Amenity::find($id);
        $newFileName = 'images_amenities_' . time() . '_' . $request->file('icon_image')->getClientOriginalName();

        // Store the file in the public disk with the new file name
        $request->file('icon_image')->storeAs('public/images/amenities', $newFileName);
        $updateAmenity->name = $request->input('name');
        $updateAmenity->icon_image = $newFileName;
        $updateAmenity->save();
        return response()->json([
            "success" => true,
            "message" => "",
            "data" => $updateAmenity,
        ]);
    }
}
