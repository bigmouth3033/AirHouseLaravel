<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function createComment(Request $request){
        $user = auth()->user()->id;
        return response()->json(
            [
                'result'=>$user
            ]
            );
    }
}
