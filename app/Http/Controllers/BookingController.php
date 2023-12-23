<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    //
    function createBooking(Request $request)
    {

        $property = Property::where('id', $request->property_id)->first();

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

        if ($property->booking_type == 'instantly') {
            $booking->booking_status = 'accepted';
        } else {
            $booking->booking_status = 'waiting';
        }

        $booking->save();

        return response($booking, 200);
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
        $renter = User::find($renter_id);


        return response()->json([
            'booking' => $booking,
            'propertyType' => $propertyName->name,
            'hostName' => $userName,
            'renter' => $renter,
        ]);
    }


    public function getAllBookingOfProperty(Request $request)
    {
        $property = Property::where('id', $request->property_id)->first();

        if ($property->user_id != $request->user()->id) {
            return response(['message' => 'cant do that'], 403);
        }

        $bookings = Booking::with('user')->where('property_id', $request->property_id);

        if ($request->startDate != null && $request->endDate != null) {
            $bookings = $bookings->where(function ($query) use ($request) {
                $query->whereDate('check_in_date', '>=', $request->startDate)
                    ->whereDate('check_in_date', '<=', $request->endDate)
                    ->orWhereDate('check_out_date', '>=', $request->startDate)
                    ->whereDate('check_out_date', '<=', $request->endDate);
            });

            $bookings = $bookings->where('booking_status', $request->booking_status);
            $bookings = $bookings->paginate(5);


            foreach ($bookings->items() as $booking) {
                if (!filter_var($booking->user->image, FILTER_VALIDATE_URL)) {
                    $booking->user->image = asset('storage/images/users/' . $booking->user->image);
                }
            }

            return response($bookings);
        }

        return response(['message' => 'bad request'], 400);
    }

    public function denyBooking(Request $request)
    {
        $booking = Booking::where('id', $request->booking_id)->first();
        $booking->booking_status = 'denied';
        $booking->save();

        return response($booking);
    }

    public function acceptBooking(Request $request)
    {
        $booking = Booking::where('id', $request->booking_id)->first();

        $violateBooking = Booking::where('booking_status', 'waiting');

        $violateBooking = $violateBooking->where(function ($query) use ($request, $booking) {
            $query->whereDate('check_in_date', '>=', $booking->check_in_date)
                ->whereDate('check_in_date', '<=', $booking->check_out_date)
                ->orWhereDate('check_out_date', '>=', $booking->check_in_date)
                ->whereDate('check_out_date', '<=', $booking->check_out_date);
        });

        $violateBooking = $violateBooking->get();

        foreach ($violateBooking as $booking) {
            $booking->booking_status = 'denied';
            $booking->save();
        }

        $booking->booking_status = 'accepted';
        $booking->save();

        return response($booking);
    }

    function getBookingByUser(Request $request)
    {
        $user = $request->user();
        $status = $request->status;
        $bookings = null;
        $perPage = 10;

        DB::statement("SET SQL_MODE=''");
        $bookings = DB::table('bookings')
            ->select('bookings.id', 'bookings.property_id', 'bookings.user_id as id_user', 'bookings.check_in_date', 'bookings.check_out_date', 'property_images.image', 'properties.user_id', 'properties.name as Property_Name', 'properties.address as Property_Address', 'users.image as user_image', 'users.first_name as user_firstName',  'users.last_name as user_lastName', 'users.email as user_Email', 'provinces.full_name as province', 'districts.full_name as districts', 'bookings.booking_status as status')
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
                ->select('bookings.id', 'bookings.property_id', 'bookings.user_id as id_user', 'bookings.check_in_date', 'bookings.check_out_date', 'property_images.image', 'properties.user_id', 'properties.name as Property_Name', 'properties.address as Property_Address', 'users.image as user_image', 'users.first_name as user_firstName',  'users.last_name as user_lastName', 'users.email as user_Email', 'provinces.full_name as province', 'districts.full_name as districts',  'bookings.booking_status as status')
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

        if ($status == 'waiting') {
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

        foreach ($bookings as $booking) {
            $booking->image = asset("storage/images/host/" . $booking->image);
        }

        return $bookings;
    }
}
