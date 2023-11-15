<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\UserResource;
use App\Models\SocialAccount;
use App\Models\Transaction;
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
            // dùng method stateless() để disable việc sử dụng session để verify state, vì ở route/api.php sẽ không đi qua middleware tạo session nên sẽ không sử dụng được session.
        ]);
    }

    public function loginCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = null;
        DB::transaction(function () use ($googleUser, &$user) {
            $socialAccount = SocialAccount::firstOrNew(
                ['social_id' => $googleUser->getId(), 'social_provider' => 'google'],
                ['social_name' => $googleUser->getName()]
            );
            if (!($user = $socialAccount->user)) {
                $user = User::create([
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                ]);
                $socialAccount->fill(['user_id' => $user->id])->save();
            }
        });
        });
        
    }
}
