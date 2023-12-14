<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    //
    function createBooking(Request $request)
    {
        $booking = new Booking();
        $booking->property_id = $request->property_id;
        $booking->user_id = $request->user()->id;
        $booking->check_in_date = $request->check_in_date;
        $booking->check_out_date = $request->check_out_date;
        $booking->price_per_day = $request->base_price;
        $booking->price_for_stay = $request->total;
        $booking->site_fees = $request->site_fees;
        $booking->booking_date = now()->toDateString();
        $booking->total_person = $request->total_person;
        $booking->booking_status = 'success';
        $booking->save();
        
        return response($booking, 200);
    }
    
    public function readBooking(Request $request){
        
        $booking = Booking::where("id" , $request->booking_id)->first();
        return response($booking, 200);
    }
}
