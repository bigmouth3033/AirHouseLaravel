<?php

namespace App\Http\Controllers;

use App\Models\Start;
use Illuminate\Http\Request;

class StartController extends Controller
{
    public function createStart(Request $request)
    {
        $user = auth()->user();
        $renter_id = $user->id;

        $start = Start::where('renter_id', $renter_id);
        $start = $start->where('property_id', $request->property_id);
        $start = $start->first();
        if ($start &&  $request->start_count != 0) {
            $start->start = $request->start_count;
            $start->save();

            return response()->json([
                "start" => $start,
                "message" => "update"
            ]);
        } else if ($start && $request->start_count == 0) {
            $start->delete();
            return response()->json([
                'message' => 'delete successfully',
            ]);
        } else {
            $start = new Start;
            $start->renter_id = $renter_id;
            $start->property_id = $request->property_id;
            $start->start = $request->start_count;
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

        $start = Start::where('renter_id', $renter_id);
        $start = $start->where('property_id', $request->property_id);
        $start = $start->first();
        return response()->json([
            'start' => $start
        ]);
    }
    // public function updateStart(Request $request)
    // {
    //     $user = auth()->user();
    //     $renter_id = $user->id;
    //     $start = Start::where('id', $request->$renter_id);
    //     $start = Start::where('id', $request->property_id);
    //     $start->first();
    //     if ($start) {
    //         $start->start = $request->start_count;
    //         $start->save();

    //         return response($start);
    //     }
    //     return response()->json([
    //         'message' => 'not found',
    //     ]);
    // }
    public function deleteStart(Request $request)
    {
        $user = auth()->user();
        $renter_id = $user->id;
        $start = Start::where('id', $request->$renter_id);
        $start = Start::where('id', $request->property_id);
        $start->first();
        if ($start && $request->start_count == 0) {
            $start->delete();
            return response()->json([
                'message' => 'successed',
            ]);
        }
        return response()->json([
            'message' => $start,
        ]);
    }
}
