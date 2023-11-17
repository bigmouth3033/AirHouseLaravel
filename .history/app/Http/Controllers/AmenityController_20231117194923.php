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
    public function update(Request $request)
    {
        $id = $request->input("id");
        $request->validate([
            'id'=>'required',
            'name' => 'required|max:50',
            'icon_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        $updateAmenity = Amenity::find($id);
        $updateAmenity->fill([
            'name' => $request->input('name'),
            // Add other fields if needed
        ]);
        $updateAmenity->update($request->all());
        return response()->json([
            "success" => true,
            "message" => "",
            "data" => $updateAmenity,
        ]);
    }
}
