<?php

namespace App\Http\Controllers;

use App\Models\Blog;

use App\Models\BlogOfCate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class BlogController extends Controller
{
    
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originFileName = $image->getClientOriginalName();
            $newFileName = 'images_blogs_' . Uuid::uuid4()->toString() . '_' . $originFileName;
            $image->storeAs('public/images/blogs', $newFileName);
            $imageUrl = asset('storage/images/blogs/' . $newFileName);

            return response()->json(['url' => $imageUrl], 200);
        }

        return response()->json(['error' => 'No image file provided'], 400);
    }

    public function create(Request $request)
    {
        $Blog = new Blog;
        $request->validate([
            'title' => 'required|string|max:300',
            'content' => 'required|string',
            'category' => 'required|array',
            'category.*' => 'int',
            // 'blog_images' => 'required',
            'blog_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,jpg',

        ]);

        $Blog->title = $request->input('title');
        $Blog->content = $request->input('content');
        $Blog->save();

        $BlogCategories = $request->input('category');
        foreach ($BlogCategories as $BlogCategory) {
            $BlogOfCate = new BlogOfCate();
            $BlogOfCate->id_blog = $Blog->id;
            $BlogOfCate->id_blog_categories = $BlogCategory;
            $BlogOfCate->updated_at = Carbon::now(); // Thêm thời gian chỉnh sửa thực tế
            $BlogOfCate->save();
        }

        //luu file hinh va tra ve duong dan
        $filePaths = [];
        if ($request->hasFile('blog_images')) {
            $files = $request->file('blog_images');
            foreach ($files as $file) {
                // Lưu tệp vào thư mục lưu trữ
                $originFileName = $file->getClientOriginalName();
                $newFileName = 'images_blogs_' . Uuid::uuid4()->toString() . '_' . $originFileName;
                $file->storeAs('public/images/blogs', $newFileName);
                $newFileName_path = asset('storage/images/blogs/' . $newFileName);
                $filePaths[] = $newFileName_path;
            }
        }
        return response()->json([
            'success' => true,
            'message' => "added ok",
            'url' => $filePaths,
            'Blog' => $Blog,
            'BlogOfCate' => $BlogOfCate,

        ]);
    }

    public function read(Request $request)
    {
        $request->validate([
            'id_blog' => 'int',
            'id_category' => 'int',
        ]);

        //để hiển thị bài viết chi tiết
        $Blog = '';
        if ($request->input('id_blog')) {
            $id_blog = $request->input('id_blog');
            $id_category = $request->input('id_category');
            $Blog = Blog::where('id', $id_blog)->first();
        }
        //để hiển thị bài viết dựa trên category
        $ListBlogThroughCateID =  [];
        if ($request->input('id_category')) {
            $id_category = $request->input('id_category');
            $ListBlogID = BlogOfCate::where('id_blog_categories', $id_category)->pluck('id_blog');
            foreach ($ListBlogID as $BlogId) {
                $BlogThroughCateID = Blog::where('id', $BlogId)->first();
                $ListBlogThroughCateID[] = $BlogThroughCateID;
            }
        }
        return response()->json([
            "success" => true,
            "data through blog_id" => $Blog,
            "data through category_id" => $ListBlogThroughCateID,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:300',
            'content' => 'required|string',
            'category' => 'array|required',

            'blog_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,jpg',
        ]);

        $id = $request->input('id');
        $Blog = Blog::where('id', $id)->first();
        $Blog->title = $request->input('title');
        $Blog->content = $request->input('content');
        $Blog->updated_at = Carbon::now();
        $Blog->save();

        //xóa hết record cũ có cùng id_blog tại bảng Blog_Of_Cate, tạo reocrd mới
        BlogOfCate::where('id_blog', $Blog->id)->delete();
        $BlogCategories = $request->input('category');

        foreach ($BlogCategories as $BlogCategory) {
            $BlogOfCate = new BlogOfCate();
            $BlogOfCate->id_blog = $Blog->id;
            $BlogOfCate->id_blog_categories = $BlogCategory;
            $BlogOfCate->updated_at = Carbon::now(); // Thêm thời gian chỉnh sửa thực tế
            $BlogOfCate->save();
        }

        if ($request->hasFile('blog_images')) {
            // // Gọi hàm 'create' và nhận giá trị trả về
            // $response = $this->create($request);
            // // Kiểm tra nếu hàm 'create' trả về thành công
            // if ($response->getStatusCode() === 200) {
            //     $responseData = json_decode($response->getContent(), true);

            //     if ($responseData['success']) {
            //         $filePaths = $responseData['newFileName_path'];

            //         // Xóa các tệp tin ảnh
            //         foreach ($filePaths as $filePath) {
            //             $fileName = basename($filePath);
            //             Storage::delete('public/images/blogs/' . $fileName);
            //         }
            //     }
            // }
            $files = $request->file('blog_images');
            $filePaths = [];
            foreach ($files as $file) {
                // Lưu tệp vào thư mục lưu trữ
                $originFileName = $file->getClientOriginalName();
                $newFileName = 'images_blogs_' . Uuid::uuid4()->toString() . '_' . $originFileName;
                $file->storeAs('public/images/blogs', $newFileName);
                $newFileName_path = asset('storage/images/blogs/' . $newFileName);
                $filePaths[] = $newFileName_path;
            }

            return response()->json([
                'success' => true,
                'message' => "added ok",
                'newFileName_path' => $filePaths,
                'Blog' => $Blog,
                'BlogOfCate' => $BlogOfCate
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Không có tệp nào được chọn nen giu tep cu.',
                'Blog' => $Blog,
                'BlogOfCate' => $BlogOfCate
            ]);
        }
    }

    public function delete($id)
    {
        $Blog = Blog::find($id);

        if ($Blog) {
            $BlogOfCates = BlogOfCate::where('id_blog', $Blog->id)->get();

            foreach ($BlogOfCates as $BlogOfCate) {
                $BlogOfCate->delete();
            }
            $Blog->delete();

            return response()->json([
                'messege' => "Deleted successfully record with ID: " . $id
            ]);
        }
        return response()->json([
            'messege' => "ID not found to delete"
        ]);
    }
}
