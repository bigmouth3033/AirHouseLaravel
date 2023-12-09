<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogOfCate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class BlogCategoryController extends Controller
{
    public function create(Request $request)
    {
        $BlogCategory = new BlogCategory;
        $BlogCategory->name = $request->input('name');
        // $BlogCategory->updated_at = Carbon::now();
        $BlogCategory->save();
    }
    public function read()
    {
        $BlogCategories = BlogCategory::all();
        return response()->json($BlogCategories);
    }

    public function update(Request $request, $id)
    {
        $BlogCategory = BlogCategory::where('id', $id)->first();
        $BlogCategory->name = $request->input('name');
        $BlogCategory->updated_at = Carbon::now();
        $BlogCategory->save();
    }

    // public function delete($id)
    // {
    //     $BlogCategory = BlogCategory::find($id);

    //     if ($BlogCategory) {
    //         $BlogOfCates = BlogOfCate::where('id_blog_categories', $BlogCategory->id)->get();
    //         $ListIDBlogFromBlogOfCates = BlogOfCate::where('id_blog_categories', $BlogCategory->id)->pluck('id_blog');

    //         $ListIDBlogFromBlogOfCates->map(function ($id_blog) {

    //         });
    //         foreach ($BlogOfCates as $BlogOfCate) {
    //             $BlogOfCate->delete();
    //         }
    //         $BlogCategory->delete();

    //         return response()->json([
    //             'messege' => "Deleted successfully record with ID: " . $id
    //         ]);
    //     }
    //     return response()->json([
    //         'messege' => "ID not found to delete"
    //     ]);
    // }
}
