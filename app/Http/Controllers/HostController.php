<?php

namespace App\Http\Controllers;


use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

use function PHPUnit\Framework\isEmpty;

class HostController extends Controller
{
    public function create(Request $request)
    {
        $Property = new Property;
        $PropertyImage = new PropertyImage;
        // $PropertyReview = new PropertyReview;

        $request->validate([
            //tb properties
            'name' => 'required|string| max:255',
            'description' => 'required|string',
            'user_id' => 'required|int',
            'property_type_id' => 'required|int',
            'room_type_id' => 'required|int',
            'category_id' => 'required|int',
            'provinces_id' => 'required|string',
            'districts_id' => 'required|string',
            'address' => 'required|string',
            'bedroom_count' => 'required|int',
            'bed_count' => 'required|int',
            'bathroom__count' => 'required|int',
            'accomodates_count' => 'required|int',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'price' => 'required|numeric',
            'minimum_stay' => 'required|int',
            //tb property_images
            // 'image' => 'required',
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic',
        ]);

        $Property->name = $request->input('name');
        $Property->description = $request->input('description');
        $Property->user_id = $request->input('user_id');
        $Property->property_type_id = $request->input('property_type_id');
        $Property->room_type_id = $request->input('room_type_id');
        $Property->category_id = $request->input('category_id');
        $Property->provinces_id = $request->input('provinces_id');
        $Property->districts_id = $request->input('districts_id');
        $Property->address = $request->input('address');
        $Property->bedroom_count = $request->input('bedroom_count');
        $Property->bed_count = $request->input('bed_count');
        $Property->bathroom__count = $request->input('bathroom__count');
        $Property->accomodates_count = $request->input('accomodates_count');
        $Property->start_date = $request->input('start_date');
        $Property->end_date = $request->input('end_date');
        $Property->price = $request->input('price');
        $Property->minimum_stay = $request->input('minimum_stay');
        $Property->save();

        // Kiểm tra xem có tệp được gửi lên hay không
        if ($request->hasFile('image')) {
            $files = $request->file('image');
            foreach ($files as $file) {
                // Lưu tệp vào thư mục lưu trữ
                $newFileName = 'images_host_' . Uuid::uuid4()->toString() . '_' . $file->getClientOriginalName();
                $uploadedFilePath = $file->storeAs('public/images/host', $newFileName);

                if (!$uploadedFilePath) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Lỗi khi lưu tệp lên máy chủ.'
                    ]);
                }

                // Lưu thông tin tệp vào cơ sở dữ liệu
                $PropertyImage = new PropertyImage;
                $PropertyImage->image = $newFileName;
                $PropertyImage->property_id = $Property->id;
                $PropertyImage->add_by_user = $request->input('user_id');
                $PropertyImage->save();
            }
            return response()->json([
                'success' => true,
                'message' => "added ok",
                'property' => $Property,
                'property_images' => PropertyImage::where('property_id', $Property->id)->get()
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No photo uploaded.'
        ]);
    }

    public function delete($id)
    {
        $Property = Property::find($id);

        if ($Property) {
            $PropertyImages = PropertyImage::where('property_id', $Property->id)->get();

            foreach ($PropertyImages as $PropertyImage) {
                Storage::delete('public/images/host/' . $PropertyImage->image);
                $PropertyImage->delete();
            }

            $Property->delete();

            return response()->json([
                'messege' => "Deleted successfully record with ID: " . $id
            ]);
        }
        return response()->json([
            'messege' => "ID not found to delete"
        ]);
    }
}
