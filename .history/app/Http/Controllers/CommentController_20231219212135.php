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
        $newComment->$user_id = $user_id;
        $newComment->comment =  $request->comment;
        $newComment->save();
        return response()->json(
            [
                'Comment'=>$newComment,
                'Property_id'=>$request->property_id,
                'Comment'=>$newComment,
                'Comment'=>$newComment,
            ]
            );
    }
}
