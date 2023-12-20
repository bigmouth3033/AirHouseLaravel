<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createComment(Request $request){
        $user = auth()->user()->id;
        $comment = $request->comment;
        $newComment = new Comment;
        $newComment->comment = $comment;
        return response()->json(
            [
                'result'=>$user
            ]
            );
    }
}
