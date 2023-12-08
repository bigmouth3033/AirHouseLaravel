<?php

namespace App\Http\Controllers;

use Ramsey\Uuid\Uuid;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    //
    public function create(Request $request)
    {
        $Property = new Property;
        $PropertyImage = new PropertyImage();
        // $PropertyReview = new PropertyReview;

        $request->validate([
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

            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $Property->name = $request->input('name');
        $Property->description = $request->input('description');
        $Property->user_id = $request->input('user_id'); // Gán 'user_id' trước khi gán 'add_by_user'
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


        // return $files = $request->file('image');
        // Kiểm tra xem có tệp được gửi lên hay không
        if ($request->hasFile('image')) {
            $files = $request->file('image');



            if (!empty($files)) {
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
                    $PropertyImage = new PropertyImage(); // Sửa lại tên biến thành $propertyImage
                    $PropertyImage->image = $newFileName;
                    $PropertyImage->property_id = $Property->id;
                    $PropertyImage->add_by_user = $request->input('user_id');
                    $PropertyImage->save();

                    if (!$PropertyImage->save()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Lỗi khi lưu thông tin tệp vào cơ sở dữ liệu.'
                        ]);
                    }
                }
                return response()->json([
                    'success' => true,
                    'message' => "added ok",
                    // 'property' => $Property,
                    'property_images' => PropertyImage::where('property_id', $Property->id)->get() // Thêm dòng này
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có tệp nào được chọn.'
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Không có tệp được tải lên.'
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

    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|string| max:255',
            'description' => 'required|string',
            // 'user_id' => 'required|int',
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

            // 'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $id = $request->input('id');
        $Property = Property::where('id', $id)->first();
        // $PropertyImage = new PropertyImage();
        $user = auth()->user();
        $user_id = $user->id;
        $Property->name = $request->input('name');
        $Property->description = $request->input('description');
        $Property->user_id = $user_id; // Gán 'user_id' trước khi gán 'add_by_user'
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


        if ($request->hasFile('image')) {
            $files = $request->file('image');
            if (!empty($files)) {
                //Neu co update anh thi xoa anh cu trong server
                $PropertyImage = PropertyImage::where('property_id', $id)->get();
                foreach ($PropertyImage as $flieImage) {
                    Storage::delete('public/images/host/' . $flieImage->image);
                    $flieImage->delete();
                }
                //them anh moi
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
                    $PropertyImage =new PropertyImage;
                    $PropertyImage->image = $newFileName;
                    $PropertyImage->property_id = $Property->id;
                    $PropertyImage->add_by_user = $user_id;
                    $PropertyImage->save();

                    if (!$PropertyImage->save()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Lỗi khi lưu thông tin tệp vào cơ sở dữ liệu.'
                        ]);
                    }
                }
                return response()->json([
                    'success' => true,
                    'message' => "updated ok",
                    'property' => $Property,
                    'property_images' => PropertyImage::where('property_id', $Property->id)->get() // Thêm dòng này
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có tệp nào được chọn nen giu tep cu.'
                ]);
            }
        }
        $listPropertyImage = PropertyImage::where('property_id', $id)->pluck('image');

            // $listPropertyImage = $listPropertyImage->map(function ($image) {
            //     return asset('storage/images/host/' . $image);
            // });
            $listPropertyImage->transform(function ($image) {
                return asset('storage/images/host/' . $image);
            });
            return response()->json([
                'success' => true,
                'property_image' => $listPropertyImage,
                'properties' => $Property
            ]);
    }

    public function read(Request $Request)
    {
        $Request->validate([
            'property_id' => 'required|int'
        ]);
        $property_id = $Request->input('property_id');
        $user = auth()->user();
        $user_id = $user->id;
        if ($user_id) {
            $listPropertyImage = PropertyImage::where('property_id', $property_id)->pluck('image');

            $listPropertyImage = $listPropertyImage->map(function ($image) {
                return asset('storage/images/host/' . $image);
            });
            // $listPropertyImage->transform(function ($image) {
            //     return asset('storage/images/host/' . $image);
            // });
            $properties = Property::find($property_id);
            return response()->json([
                'success' => true,
                'property_image' => $listPropertyImage,
                'properties' => $properties
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'khong co hoster này'
            ]);
        }
    }

    public function readById(Request $request)
    {
        $property = Property::with('user', 'category', 'property_type', 'room_type', 'district', 'province', 'amenities', 'images')->where('id', $request->id)->first();

        foreach ($property->images as $key => $image) {
            $property->images[$key] = asset("storage/images/host/" . $image->image);
        }
        foreach ($property->amenities as $key => $amenity) {
            $property->amenities[$key]->image = asset("storage/images/amenities/" . $amenity->image);
        }
        if ($property) {
            // return response()->json([
            //     'data'=>$property->images
            // ]);
            return response($property);
        }
    }
}
