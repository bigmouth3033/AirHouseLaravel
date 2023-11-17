<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{

    //Không chạy được thì đây sửa table đi pro
    // ALTER TABLE property_type
    // CHANGE `icon-image` `icon_image` VARCHAR(255)
    // CHANGE `updated_at` `updated_at` datetime NOT NULL DEFAULT current_timestamp()


    public function create(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|max:50',
            'iconName' => 'required|image|mimes:jpeg,png,jpg,gif,svg', // Adjust validation rules for the image
        ]);

        $PropertyType = new PropertyType;
        $PropertyType->name = $validatedData['name'];

        $newFileName = 'images_property_type_' . time() . '_' . $request->file('iconName')->getClientOriginalName();

        $request->file('iconName')->storeAs('public/images/property_type', $newFileName);

        $PropertyType->icon_image = $newFileName;
        $PropertyType->save();

        return response()->json([
            "success" => true,
            "message" => "A property type created successfully.",
            "data" => $PropertyType,
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
        $updatePropertyType = PropertyType::find($id);
        if (!$updatePropertyType) {
            return response()->json([
                "success" => false,
                "message" => "ID does not exist. Update unsuccessful!!!",
            ], 404);
        }
        $newFileName = 'images_property_type_' . time() . '_' . $request->file('icon_image')->getClientOriginalName();

        $request->file('icon_image')->storeAs('public/images/property_type', $newFileName);
        $updatePropertyType->name = $request->input('name');
        $updatePropertyType->icon_image = $newFileName;
        $updatePropertyType->save();
        return response()->json([
            "success" => true,
            "message" => " Property type have id : ". $id ." updated successfully.",
            "data" => $updatePropertyType,
        ]);
    }
    public function read()
    {
        // Lấy tất cả các bản ghi từ bảng Amenity cùng với quan hệ 'icon_image'
        $PropertyType = PropertyType::all();

        // Duyệt qua mỗi Amenity và cập nhật đường dẫn 'icon_image'
        foreach ($PropertyType as $propertyType) {
            // Kiểm tra xem 'icon_image' có giá trị không trống
            if ($propertyType->icon_image !== null) {
                // Tạo đường dẫn mới sử dụng hàm asset và cập nhật thuộc tính 'icon_image'
                $propertyType->icon_image = asset('storage/images/property_type/' . $propertyType->icon_image);
            }
        }
        // Trả về phản hồi JSON với thông tin về sự thành công và dữ liệu đã được cập nhật
        return response()->json([
            "success" => true,
            "message" => "All amenity.",
            "data" => $PropertyType,
        ]);
    }
}
