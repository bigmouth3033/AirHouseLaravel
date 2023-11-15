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

    // xử lý quá trình callback sau khi người dùng xác thực thành công trên trang xác thực Google.
    public function loginCallback()
    {
        // Sử dụng thư viện Socialite để lấy thông tin người dùng đã xác thực từ Google.
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = null;
        // Bắt đầu một giao dịch cơ sở dữ liệu để đảm bảo tính nhất quán trong quá trình tạo người dùng và tài khoản mạng xã hội.
        DB::transaction(function () use ($googleUser, &$user) {
            // Kiểm tra xem đã có một tài khoản mạng xã hội với social_id và social_provider tương ứng chưa, nếu chưa thì tạo mới.
            // firstOrNew sẽ trả về một đối tượng SocialAccount có sẵn hoặc tạo mới nếu không tìm thấy.
            $socialAccount = SocialAccount::firstOrNew(
                ['social_id' => $googleUser->getId(), 'social_provider' => 'google'],
                ['social_name' => $googleUser->getName()]
            );
            // Kiểm tra xem đã liên kết tài khoản mạng xã hội với một người dùng cụ thể chưa. Nếu chưa, tạo một người dùng mới.
            if (!($user = $socialAccount->user)) {
                // Tạo một người dùng mới nếu chưa có liên kết nào với tài khoản mạng xã hội.
                $user = User::create([
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                ]);
                $socialAccount->fill(['user_id' => $user->id])->save();
            }
        });
        // Trả về một phản hồi JSON chứa thông tin người dùng và thông tin người dùng từ Google.
        return Response::json([
            // Định dạng dữ liệu người dùng trước khi trả về.
            'user' => new UserResource($user),
            // Đưa thông tin người dùng từ Google vào phản hồi, có thể sử dụng trong frontend.
            'google_user' => $googleUser,
        ]);
        
    }
}

// Lấy user từ Google: $googleUser = Socialite::driver('google')->stateless()->user();, rất đơn giản do tất cả logic phức tạp để gọi đến Google đã được xử lý bởi Socialite
// Tạo đối tượng model SocialAccount, sau đó nếu tài khoản này chưa liên kết với user nào thì sẽ tạo một tài khoản user mới
// Sau đó tùy theo logic của web và cách thực hiện authentication (jwt, passport) mà bạn sẽ xử lý. Chẳng hạn tạo một jwt token để user có thể đăng nhập hoặc set trạng thái là chưa active và đợi đến khi admin activate thì mới được đăng nhập...