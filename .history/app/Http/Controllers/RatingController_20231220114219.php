<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function createStart(Request $request)
    {

        $user = auth()->user();
        $renter_id = $user->id;
        $property_id = $request->property_id;
        // Lấy id user kiểm tra xem người này có rating chưa
        $start = Rating::where('renter_id', $renter_id);
        $start = $start->where('property_id', $property_id);
        $start = $start->first();
        //Nếu đã từng rating thi cho update
        //Kiểm tra số sao update có hợp lệ không
        if ($start) {
            $start->start = $request->rating;
            $start->message = $request->preview;
            $start->save();
            return response()->json([
                "start" => $start,
                "message" => "update"
            ]);
        }
        //Ngược tại tạo một racord rating
        else {
            $booking = Booking::where('user_id', $renter_id)->where('property_id', $property_id)->first();
            // $booking = $booking->where('booking_status')->first();
            if ($booking) {
                // $rating = 
                $start = new Rating;
                $start->renter_id = $renter_id;
                $start->property_id = $request->property_id;
                $start->message = $request->preview;
                $start->start = $request->rating;
                $start->save();
                return response()->json([
                    "start" => $start,
                    "message" => "new"
                ]);
            }else{
                return response()->json([
                    'message' => "not yet rented"
                ]);
            }
        }
    }
    public function readStart(Request $request)
    {
        $user = auth()->user();
        $renter_id = $user->id;

        $start = Rating::where('renter_id', $renter_id);
        $start = $start->where('property_id', $request->property_id);
        $start = $start->first();
        return response()->json([
            'start' => $start
        ]);
    }
    public function readAverageStart(Request $request){
        $property_id = $request->property_id;
        
        $listRating = Rating::where('property_id', $property_id)->get();
        $total = 0;
        $count = 0;
        foreach($listRating as $rating){
            $total= $rating->start + $total;
            $count++;
        }
        $average = $total/$count;
        $result = number_format($average, 1, '.', '');
        return response()->json([
            "average" => $result
        ]);
    }
}
