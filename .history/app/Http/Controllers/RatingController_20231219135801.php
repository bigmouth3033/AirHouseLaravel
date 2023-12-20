<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function createStart(Request $request)
    {

        $user = auth()->user();
        $renter_id = $user->id;
        // Lấy id user kiểm tra xem người này có rating chưa
        $start = Rating::where('renter_id', $renter_id);
        $start = $start->where('property_id', $request->property_id);
        $start = $start->first();
        //Nếu đã từng rating thi cho update
        //Kiểm tra số sao update có hợp lệ không
        if ($start) {
            $start->start = $request->rating;
            $start->save();
            return response()->json([
                "start" => $start,
                "message" => "update"
            ]);
        }
        //Ngược tại tạo một racord rating
        else {
            $start = new Rating;
            $start->renter_id = $renter_id;
            $start->property_id = $request->property_id;
            $start->start = $request->rating;
            $start->save();
            return response()->json([
                "start" => $start,
                "message" => "new"
            ]);
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
}
