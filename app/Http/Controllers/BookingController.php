<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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


    function getBookingByUser(Request $request)
    {
        $user = $request->user();
        $perPage = 10;

        DB::statement("SET SQL_MODE=''");
        $bookings = DB::table('bookings')
            ->select('bookings.id', 'bookings.property_id', 'bookings.user_id as id_user', 'bookings.check_in_date', 'bookings.check_out_date', 'property_images.image', 'properties.user_id', 'properties.name as Property_Name', 'properties.address as Property_Address', 'users.image as user_image', 'users.first_name as user_firstName',  'users.last_name as user_lastName', 'users.email as user_Email')
            ->join('property_images', 'property_images.property_id', '=', 'bookings.property_id')
            ->join('properties', 'properties.id', '=', 'bookings.property_id')
            ->join('users', 'users.id', '=', 'properties.user_id')
            // ->join('provinces', 'provinces.code', '=', 'properties.provinces_id')
            // ->join('districts', 'districts.code', '=', 'properties.districts_id')
            ->where('bookings.user_id', $user->id)
            ->groupBy('bookings.id')
            ->paginate($perPage);



        return $bookings;
    }

    function readCurrentPage(Request $request)
    {
        $user = $request->user();
        $perPage = 10;
        DB::statement("SET SQL_MODE=''");
        $bookings = DB::table('bookings')
            ->select(
                'bookings.property_id',
                'bookings.user_id',
                'bookings.check_in_date',
                'bookings.check_out_date',
                'properties.user_id AS property_user_id',
                'properties.address',
                'users.first_name',
                'users.last_name',
                'users.image AS user_image',
                'property_images.image AS property_image',
                'provinces.full_name AS province_full_name',
                'districts.full_name AS district_full_name'
            )
            ->join('properties', 'properties.id', '=', 'bookings.property_id')
            ->join('users', 'users.id', '=', 'bookings.user_id')
            ->join('property_images', 'property_images.property_id', '=', 'bookings.property_id')
            ->join('provinces', 'provinces.code', '=', 'properties.provinces_id')
            ->join('districts', 'districts.code', '=', 'properties.districts_id')
            ->where('properties.user_id', $user->id)
            ->groupBy('bookings.id')
            ->paginate($perPage);

        return $bookings;
    }
}
