<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\UserResource;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
