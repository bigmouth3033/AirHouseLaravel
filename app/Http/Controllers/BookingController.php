<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Property;
use App\Models\PropertyType;
use Carbon\Carbon;
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
        $booking->booking_status = 'accepted';
        $booking->save();

        return response($booking, 200);
    }


    function getBookingByUser(Request $request)
    {
        $user = $request->user();
        $status = $request->status;
        $bookings = null;
        $perPage = 10;

        DB::statement("SET SQL_MODE=''");
        $bookings = DB::table('bookings')
            ->select('bookings.id', 'bookings.property_id', 'bookings.user_id as id_user', 'bookings.check_in_date', 'bookings.check_out_date', 'property_images.image', 'properties.user_id', 'properties.name as Property_Name', 'properties.address as Property_Address', 'users.image as user_image', 'users.first_name as user_firstName',  'users.last_name as user_lastName', 'users.email as user_Email', 'provinces.full_name as province', 'districts.full_name as districts')
            ->join('property_images', 'property_images.property_id', '=', 'bookings.property_id')
            ->join('properties', 'properties.id', '=', 'bookings.property_id')
            ->join('users', 'users.id', '=', 'properties.user_id')
            ->join('provinces', 'provinces.code', '=', 'properties.provinces_id')
            ->join('districts', 'districts.code', '=', 'properties.districts_id')
            ->where('bookings.user_id', $user->id)            
            ->groupBy('bookings.id')
            ->paginate($perPage);

        if ($status == 'accepted') {
            DB::statement("SET SQL_MODE=''");
            $bookings = DB::table('bookings')
                ->select('bookings.id', 'bookings.property_id', 'bookings.user_id as id_user', 'bookings.check_in_date', 'bookings.check_out_date', 'property_images.image', 'properties.user_id', 'properties.name as Property_Name', 'properties.address as Property_Address', 'users.image as user_image', 'users.first_name as user_firstName',  'users.last_name as user_lastName', 'users.email as user_Email', 'provinces.full_name as province', 'districts.full_name as districts')
                ->join('property_images', 'property_images.property_id', '=', 'bookings.property_id')
                ->join('properties', 'properties.id', '=', 'bookings.property_id')
                ->join('users', 'users.id', '=', 'properties.user_id')
                ->join('provinces', 'provinces.code', '=', 'properties.provinces_id')
                ->join('districts', 'districts.code', '=', 'properties.districts_id')
                ->where('bookings.user_id', $user->id)
                ->where('booking_status', $status)
                ->groupBy('bookings.id')
                ->paginate($perPage);
        }

        if ($status == 'success') {
            DB::statement("SET SQL_MODE=''");
            $bookings = DB::table('bookings')
                ->select('bookings.id', 'bookings.property_id', 'bookings.user_id as id_user', 'bookings.check_in_date', 'bookings.check_out_date', 'property_images.image', 'properties.user_id', 'properties.name as Property_Name', 'properties.address as Property_Address', 'users.image as user_image', 'users.first_name as user_firstName',  'users.last_name as user_lastName', 'users.email as user_Email', 'provinces.full_name as province', 'districts.full_name as districts')
                ->join('property_images', 'property_images.property_id', '=', 'bookings.property_id')
                ->join('properties', 'properties.id', '=', 'bookings.property_id')
                ->join('users', 'users.id', '=', 'properties.user_id')
                ->join('provinces', 'provinces.code', '=', 'properties.provinces_id')
                ->join('districts', 'districts.code', '=', 'properties.districts_id')
                ->where('bookings.user_id', $user->id)
                ->where('booking_status', $status)
                ->groupBy('bookings.id')
                ->paginate($perPage);
        }
        if ($status == 'denied') {
            DB::statement("SET SQL_MODE=''");
            $bookings = DB::table('bookings')
                ->select('bookings.id', 'bookings.property_id', 'bookings.user_id as id_user', 'bookings.check_in_date', 'bookings.check_out_date', 'property_images.image', 'properties.user_id', 'properties.name as Property_Name', 'properties.address as Property_Address', 'users.image as user_image', 'users.first_name as user_firstName',  'users.last_name as user_lastName', 'users.email as user_Email', 'provinces.full_name as province', 'districts.full_name as districts')
                ->join('property_images', 'property_images.property_id', '=', 'bookings.property_id')
                ->join('properties', 'properties.id', '=', 'bookings.property_id')
                ->join('users', 'users.id', '=', 'properties.user_id')
                ->join('provinces', 'provinces.code', '=', 'properties.provinces_id')
                ->join('districts', 'districts.code', '=', 'properties.districts_id')
                ->where('bookings.user_id', $user->id)
                ->where('booking_status', $status)
                ->groupBy('bookings.id')
                ->paginate($perPage);
        }

        if ($status == 'expired') {
            $now = Carbon::now();

            DB::statement("SET SQL_MODE=''");
            $bookings = DB::table('bookings')
                ->select('bookings.id', 'bookings.property_id', 'bookings.user_id as id_user', 'bookings.check_in_date', 'bookings.check_out_date', 'property_images.image', 'properties.user_id', 'properties.name as Property_Name', 'properties.address as Property_Address', 'users.image as user_image', 'users.first_name as user_firstName',  'users.last_name as user_lastName', 'users.email as user_Email', 'provinces.full_name as province', 'districts.full_name as districts')
                ->join('property_images', 'property_images.property_id', '=', 'bookings.property_id')
                ->join('properties', 'properties.id', '=', 'bookings.property_id')
                ->join('users', 'users.id', '=', 'properties.user_id')
                ->join('provinces', 'provinces.code', '=', 'properties.provinces_id')
                ->join('districts', 'districts.code', '=', 'properties.districts_id')
                ->where('bookings.user_id', $user->id)
                ->where('check_in_date', '<', $now)
                ->groupBy('bookings.id')
                ->paginate($perPage);
        }


        foreach ($bookings as $booking) {
            $booking->image = asset("storage/images/host/" . $booking->image);
        }

        return $bookings;
    }

    public function readBooking(Request $request)
    {
        $user = auth()->user();
        $renter_id = $user->id;

        $booking = Booking::where("id", $request->booking_id)->first();
        $booking = Booking::with('property')
            ->where('id', $request->booking_id)
            ->where('user_id', $renter_id)
            ->first();

        $propertyName = PropertyType::find($booking->property->property_type_id);
        $userName = User::find($booking->property->user_id);

        return response()->json([
            'booking' => $booking,
            'PropertyName' => $propertyName->name,
            'userName' => $userName
        ]);
    }

    function getBookingByStatus(Request $request)
    {
        $perPage = 10;
        $user = $request->user();
        $status = $request->status;
        DB::statement("SET SQL_MODE=''");
        $bookings = DB::table('bookings')
            ->select('bookings.id', 'bookings.property_id', 'bookings.user_id as id_user', 'bookings.check_in_date', 'bookings.check_out_date', 'property_images.image', 'properties.user_id', 'properties.name as Property_Name', 'properties.address as Property_Address', 'users.image as user_image', 'users.first_name as user_firstName',  'users.last_name as user_lastName', 'users.email as user_Email', 'provinces.full_name as province', 'districts.full_name as districts')
            ->join('property_images', 'property_images.property_id', '=', 'bookings.property_id')
            ->join('properties', 'properties.id', '=', 'bookings.property_id')
            ->join('users', 'users.id', '=', 'properties.user_id')
            ->join('provinces', 'provinces.code', '=', 'properties.provinces_id')
            ->join('districts', 'districts.code', '=', 'properties.districts_id')
            ->where('bookings.user_id', $user->id)
            ->where('booking_status', $status)
            ->groupBy('bookings.id')
            ->paginate($perPage);


        foreach ($bookings as $booking) {
            $booking->image = asset("storage/images/host/" . $booking->image);
        }
        return $bookings;
    }

    function getBookingByExpired(Request $request)
    {
        $perPage = 10;
        $user = $request->user();
        $now = Carbon::now();

        DB::statement("SET SQL_MODE=''");
        $bookings = DB::table('bookings')
            ->select('bookings.id', 'bookings.property_id', 'bookings.user_id as id_user', 'bookings.check_in_date', 'bookings.check_out_date', 'property_images.image', 'properties.user_id', 'properties.name as Property_Name', 'properties.address as Property_Address', 'users.image as user_image', 'users.first_name as user_firstName',  'users.last_name as user_lastName', 'users.email as user_Email', 'provinces.full_name as province', 'districts.full_name as districts')
            ->join('property_images', 'property_images.property_id', '=', 'bookings.property_id')
            ->join('properties', 'properties.id', '=', 'bookings.property_id')
            ->join('users', 'users.id', '=', 'properties.user_id')
            ->join('provinces', 'provinces.code', '=', 'properties.provinces_id')
            ->join('districts', 'districts.code', '=', 'properties.districts_id')
            ->where('bookings.user_id', $user->id)
            ->where('check_in_date', '<', $now)
            ->groupBy('bookings.id')
            ->paginate($perPage);

        foreach ($bookings as $booking) {
            $booking->image = asset("storage/images/host/" . $booking->image);
        }

        return $bookings;
    }






    public function TestApi()
    {
        $users = User::pluck('id');
        $user = fake()->randomElement($users);
        $properties = Property::where('user_id', '!=', $user)->pluck('id');
        $properties_2 = Property::pluck('id');




        return response()->json([
            '$user' => $user,
            '$properties' => count($properties),
            '$properties_2' => count($properties_2)
        ]);
    }
}
