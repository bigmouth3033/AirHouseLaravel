<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AmenityController extends Controller
{
    //php artisan storage:link

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
            "message" => "A amenity created successfully.",
            "data" => $Amenity,
        ]);
    }

    public function read()
    {
        // Lấy tất cả các bản ghi từ bảng Amenity cùng với quan hệ 'icon_image'
        $Amenities = Amenity::all();

        // Duyệt qua mỗi Amenity và cập nhật đường dẫn 'icon_image'
        foreach ($Amenities as $amenity) {
            // Kiểm tra xem 'icon_image' có giá trị không trống
            if ($amenity->icon_image !== null) {
                // Tạo đường dẫn mới sử dụng hàm asset và cập nhật thuộc tính 'icon_image'
                $amenity->icon_image = asset('storage/images/amenities/' . $amenity->icon_image);
            }
        }
        // Trả về phản hồi JSON với thông tin về sự thành công và dữ liệu đã được cập nhật
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
        $newFileName = 'images_amenities_' . time() . '_' . $request->file('icon_image')->getClientOriginalName();

        // Store the file in the public disk with the new file name
        $request->file('icon_image')->storeAs('public/images/amenities', $newFileName);
        $updateAmenity->name = $request->input('name');
        $updateAmenity->icon_image = $newFileName;
        $updateAmenity->save();
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
        if (Amenity::find($id)) {
            Amenity::find($id)->delete();
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

        if ($Amenities) {
            foreach ($Amenities as $amenity) {
                if ($amenity->icon_image != "") {
                    # code...
                    $amenity->icon_image = asset('storage/images/amenities/' . $amenity->icon_image);
                }
            }
            return response()->json([
                "success" => true,
                "message" => "",
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
