<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // API get google login url
    // trả về URL dẫn đến trang xác thực của Google.
    public function loginUrl()
    {
        return Response::json([
            // Tạo URL để chuyển hướng người dùng đến trang xác thực Google.
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function loginCallback()
    {
        //
    }
}
