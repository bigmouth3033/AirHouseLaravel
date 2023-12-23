<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\PropertyType;
use App\Models\PropertyExceptionDate;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    //
    function createBooking(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $now = today()->toDateString();
            //exceptiondate
            $listException = [];
            $cntExcept = 0;
            $exception_start = PropertyExceptionDate::where('property_id', $request->property_id)->where('start_date', '>=', $now)->pluck('start_date')->toArray();
            $exception_end = PropertyExceptionDate::where('property_id', $request->property_id)->where('end_date', '>=', $now)->pluck('end_date')->toArray();

            foreach ($exception_start as $bookingIn) {
                $bookingIn = Carbon::parse($bookingIn);
                $bookingOut = Carbon::parse($exception_end[$cntExcept]);
                for ($date = $bookingIn; $date->lte($bookingOut); $date->addDay()) {
                    $listException[] = $date->toDateString();
                }
                $cntExcept++;
            }

            //list check_in_date
            $booking_in = Booking::where('check_in_date', '>=', $now);
            $booking_in = $booking_in->where('property_id',  $request->property_id)->where(function ($query) {
                $query->where('booking_status', 'waiting')
                    ->orWhere('booking_status', 'success');
            });
            $booking_in = $booking_in->pluck('check_in_date')->toArray();
            //list check_out_date
            $booking_out = Booking::where('check_in_date', '>=', $now);
            $booking_out = $booking_out->where('property_id', $request->property_id)->where(function ($query) {
                $query->where('booking_status', 'waiting')
                    ->orWhere('booking_status', 'success');
            });
            $booking_out = $booking_out->pluck('check_out_date')->toArray();
            $listBookedDate = [];
            $cntBook = 0;
            foreach ($booking_in as $bookingIn) {
                $bookingIn = Carbon::parse($bookingIn);
                $bookingOut = Carbon::parse($booking_out[$cntBook]);
                for ($date = $bookingIn; $date->lte($bookingOut); $date->addDay()) {
                    $listBookedDate[] = $date->toDateString();
                }
                $cntBook++;
            }
            //Xu ly yeu cau cu client dang muon book
            $checkInDate = Carbon::parse($request->check_in_date);
            $checkOutDate = Carbon::parse($request->check_out_date);
            // Tạo mảng chứa tất cả các ngày giữa check_in_date và check_out_date ma client dangv muon book
            $datesInRange = [];
            for ($date = $checkInDate; $date->lte($checkOutDate); $date->addDay()) {
                $datesInRange[] = $date->toDateString();
            }
            if (array_intersect($listBookedDate, $datesInRange) || array_intersect($listException, $datesInRange)) {
                return response("error: maching date", 403);
            } else {
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
        } else {
            return response("error", 404);
        }
    }


    function getBookingByUser(Request $request)
    {
        $user = $request->user();
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
        if ($booking) {
            $booking = $booking->where("id", $request->booking_id)->where('booking_status', "accepted")->first();
            if ($booking) {
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
            } else {
                return response([
                    'error' => 'Not Found',
                    'status' => 404,
                ], 404);
                
            };
        } else {
            return response([
                'error' => 'Not Found',
                'status' => 403,
            ], 403);
            
        };
    }
}
