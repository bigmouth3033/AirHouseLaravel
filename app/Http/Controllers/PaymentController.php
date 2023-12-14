<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        try {

            // Thiết lập khóa bảo mật của bạn từ trang quản lý Stripe
            Stripe::setApiKey('sk_test_51OMEQvCQa88qCWLBZ9D8WTgGTJpnvj4izRhNcEqokQrWIXWJmtYKGBSdAI3MQiEZa7keADfiovgnxj6NKq36Tdt900O5t2PBK7');

            // Tạo một PaymentIntent trên máy chủ
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount,
                'currency' => 'usd',  // Điều chỉnh theo đơn vị tiền tệ của bạn
                // Thêm các thông tin khác theo cần thiết
            ]);

            // Trả về clientSecret cho máy khách
            return response()->json(['clientSecret' => $paymentIntent->client_secret]
            // Xử lý lỗi nếu có]
        );
        } catch (\Exception $e) {
            // Xử lý lỗi nếu c
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function success(Request $request){
        $transaction = new Test;
        $transaction->paymentid = $request->payment_intent;
        $transaction->save();
        return response($transaction) ;
    }
    public function readSuccess(Request $request){
        $id= $request->payment_intent;
        $transaction = Test::where('paymentid', $id)->first();
        return response($transaction) ;
    }

}
