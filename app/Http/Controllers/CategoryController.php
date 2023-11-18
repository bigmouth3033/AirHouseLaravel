<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    // function getData(){
    //     return Category::all();
    // }

    public function create(Request $request)
    {
        // tạo biến instance từ model Category để truy vấn vào các cột của bảng
        $Category = new Category;

        $validatedData = $request->validate([
            'name' => 'required|string| max:255',
            'iconName' => 'required| mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required|string'
        ]);

        //check đầu vào và gán cho các thuộc tính của object Categpry

        //cách 1: trực tiếp gán giá trị từ biến $request vào các thuộc tính của đối tượng $Category.
        // $Category->name = $request->name;
        // $Category->icon = $request->iconName;
        // $Category->description = $request->description;

        //bên trái: tên cột trong database, bên phải trùng key của validate
        $Category->name = $validatedData['name'];
        $Category->icon = $validatedData['iconName'];
        $Category->description = $validatedData['description'];

        $Category->save();
        return response()->json(
            [
                'status' => true,
                "messege" => "added ok",
                "object" => $Category
            ]
        );
    }
}
