<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class CategoryController extends Controller
{
    //
    public function create(Request $request)
    {
        // tạo biến instance từ model Category để truy vấn vào các cột của bảng
        $Category = new Category;

        $validatedData = $request->validate([
            'name' => 'required|string| max:255',
            'icon_image' => 'required| mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required|string'
        ]);

        //check đầu vào và gán cho các thuộc tính của object Categpry

        //cách 1: trực tiếp gán giá trị từ biến $request vào các thuộc tính của đối tượng $Category.
        // $Category->name = $request->name;
        // $Category->icon = $request->iconName;
        // $Category->description = $request->description;

        //bên trái: tên cột trong database, bên phải trùng key của validate
        $Category->name = $validatedData['name'];
        $Category->description = $validatedData['description'];

        //tạo biến chứa tên mới
        $newFileName = 'images_category_' . Uuid::uuid4()->toString() . '_' . $request->file('icon_image')->getClientOriginalName();

        //lưu file vào thư mục với đường dẫn xxx
        $request->file('icon_image')->storeAs('public/images/category', $newFileName);

        //insert tên file mới vào cột 'icon' trong bảng
        $Category->icon_image = $newFileName;
        $Category->save();


        //lay duong dan cho hinh anh truoc khi tra du lieu ve
        $newFileName_path = asset('storage/images/category/' . $newFileName);
        $Category->icon = $newFileName_path;

        return response()->json(
            [
                'suscess' => true,
                'messege' => "added ok",
                'object' => $Category
            ]
        );
    }

    public function read()
    {
        $Categories = Category::all();

        foreach ($Categories as $category) {
            if ($category->icon !== null) {
                $category->icon = asset('storage/images/category/' . $category->icon);
            } else {
                $category->icon = null;
            }
        }
        return response()->json([
            "success" => true,
            "message" => "All Categories.",
            "data" => $Categories,
        ]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'name' => 'string| min:1',
            'icon_image' => 'mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required|string'
        ]);

        $id = $request->input('id');  //k gửi tham số ID cho function update thì làm vầy

        //check if $id exist
        $Category = Category::find($id);  //trả về bản ghi(record) hoặc NULL

        if ($Category) {
            //gán value mới cho từng cột
            $Category->name = $validatedData['name'];
            $Category->description = $validatedData['description'];

            if ($request->file('icon_image')) {
                Storage::delete('public/images/category/' . $Category->icon_image);
                $newFileName = 'images_category_update_' . Uuid::uuid4()->toString() . '_' . $request->file('icon_image')->getClientOriginalName();
                $request->file('icon_image')->storeAs('public/images/category', $newFileName);
                $Category->icon_image = $newFileName;
            }

            $Category->update();

            return response()->json([
                'messege' => "Updated successfully",
            ]);
        } else {
            return response()->json(['messege' => "ID not found", 404]);
        }
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $id = $request->input("id");
        $Category = Category::find($id);
        if ($Category) {
            unlink(storage_path('app/public/images/category/' . $Category->icon_image));
            $Category->delete();
            return response()->json([
                'messege' => "Deleted successfully record with ID: " . $id
            ]);
        }
        return response()->json([
            'messege' => "ID not found to delete"
        ]);
    }

    public function filterByName(Request $request)
    {
        $name = $request->input('name');
        $Categories = Category::where('name', 'like', '%' . $name . '%')->get();
        // Để kiểm tra xem Collection có rỗng hay không, sử dụng phương thức isEmpty() hoặc count()
        if (count($Categories) > 0) {
            foreach ($Categories as $Category) {
                if ($Category->icon_image != "") {
                    $Category->icon_image = asset('storage/images/category/' . $Category->icon_image);
                } else {
                    $Category->icon_image = null;
                }
            }
            return response()->json([
                "success" => true,
                "message" => "All of Categories list",
                "data" => $Categories,
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Category not found.",
            ], 404);
        }
    }

    public function filterById(Request $request)
    {
        $id = $request->input("id");
        $category = Category::where('id',  $id)->first();

        if ($category) {
            $category->icon_image = asset('storage/images/category/' . $category->icon_image);
            return response()->json([$category]);
        } else {
            return response([
                'message' => 'not found'
            ]);
        }
    }

    public function readCurrentPage($currentPage)
    {
        $total = Category::count();
        $collections = Category::all()->reverse()->chunk(10);
        $collection = $collections[$currentPage - 1];

        $newCollection = [];
        foreach ($collection as $key => $chunk) {
            array_push($newCollection,  $chunk);
        }
        $collection = $newCollection;

        foreach ($collection as $category) {
            if ($category->icon_image !== null) {
                $category->icon_image = asset('storage/images/category/' . $category->icon_image);
            } else {
                $category->icon_image = null;
            }
        }
        return response()->json([
            'items' => $collection,
            'total' => $total
        ]);
    }
}