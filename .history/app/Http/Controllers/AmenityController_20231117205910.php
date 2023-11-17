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
            "message" => "",
            "data" => $Amenities,
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
    public function delete(Request $request){
        $request->validate([
            'id'=>'required',
        ]);
        $id = $request->input("id");
        if (Amenity::find($id)) {
            Amenity::find($id)->delete();
            return response()->json([
                "success"=> true,
                "message"=> "Xoa thanh cong amentity co id =" . $id,
            ]);
        }else{
            return response()->json([
                "success"=> false,
                "message"=> "Id khong ton tai.Xoa khong thanh cong!!!"
            ]);
        }
        
    }
}
