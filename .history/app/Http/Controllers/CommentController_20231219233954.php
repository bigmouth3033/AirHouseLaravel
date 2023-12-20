<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createComment(Request $request){
        $user_id = auth()->user()->id;
        $newComment = new Comment;
        $newComment->property_id = $request->property_id;
        $newComment->user_id = $user_id;
        $newComment->comment =  $request->comment;
        $newComment->save();
        return response()->json(
            [
                'Comment'=>$newComment,
              
            ]
            );
    }
    public function readCommentPage(Request $request){
        $page = $request->page;
        $property_id= $request->property_id;
        $currentPage = $page*5;
        $total = Comment::where('property_id',$property_id)->count();
        $listComment = Comment::where('property_id',$property_id)->orderBy('created_at', 'desc')->paginate($currentPage);
        return response($listComment);
    }
}
