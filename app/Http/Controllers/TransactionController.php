<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Test;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class TransactionController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        // Thiết lập khóa bảo mật của bạn từ trang quản lý Stripe
        Stripe::setApiKey('sk_test_51OMEQvCQa88qCWLBZ9D8WTgGTJpnvj4izRhNcEqokQrWIXWJmtYKGBSdAI3MQiEZa7keADfiovgnxj6NKq36Tdt900O5t2PBK7');
        // Tạo một PaymentIntent trên máy chủ
        $user = auth()->user();
        $renter_id = $user->id;

        $booking = Booking::where('id', $request->booking_id);
        $booking = $booking->where('user_id', $renter_id);
        $booking = $booking->where('booking_status', "accepted")->first();

        if ($booking) {
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount,
                'currency' => 'usd',
            ]);
            return response()->json(
                ['clientSecret' => $paymentIntent->client_secret]
            );
        } else {
            return response()->json(
                ['message' => " not allow payment"]
            );
        }
    }
    public function success(Request $request)
    {
        $transaction = new Transaction;

        // $booking_status = $request->input("booking_status");
        $booking_id = $request->input("booking_id");
        $paymentid = $request->payment_intent;

        //Update booking information for booking
        $booking = Booking::where('id',  $booking_id)->first();
        if ($booking && $paymentid) {
            $booking->booking_status = "success";
            $booking->save();

            //hosting_id
            $property = Property::where('id', $booking->property_id)->first();
            //create a new transaction object for the booking     
            $transaction->payment_id = $paymentid;
            $transaction->property_id = $booking->property_id;
            $transaction->reciever_id  = $booking->user_id;
            $transaction->payee_id  = $property->user_id;
            $transaction->booking_id = $booking_id;
            $transaction->site_fees = $booking->site_fees;
            $transaction->amout = $booking->price_for_stay;
            $transaction->transfer_on = now()->toDateTimeString();
            $transaction->save();
            return response()->json([
                'transaction' => $transaction,
                'booking' => $booking
            ]);
        } else {
            return response()->json([
                'booking_id' => $booking_id,
                'payment_id' => $paymentid,
            ]);
        }
    }
    public function readSuccess(Request $request)
    {
        $id = $request->input('payment_intent');
        $transaction = Transaction::where('payment_id', $id)->first();
        return response($transaction);
    }
}
