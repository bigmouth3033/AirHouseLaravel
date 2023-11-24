<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomTypeController extends Controller
{
    //
    function getRooms()
    {
        $users = RoomType::all();
        $array = $users->map(
            function (RoomType $roomtype) {
                $roomtype->img_URL = asset('images/room_images/' . $roomtype->icon_image);
                return $roomtype;
            }
        );
        return $array;
    }

    function create(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:room_type|max:50',
            'icon_image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);


        $imageName = time() . '.' . $request->file('image')->extension();
        $request->file('icon_image')->storeAs('public/images/room_images', $imageName);

        $checkDB = DB::table('room_type')->insert(
            [
                'name' => $request['name'],
                'icon_image' => $imageName
            ]
        );

        if (!$checkDB) {
            return response([
                'message' => 'fail to add'
            ]);
        }

        return response([
            'message' => 'sucess'
        ]);;
    }

    function getRoom(Request $request)
    {

        $request->validate([
            'name' => 'required|max:50'
        ]);

        $room = DB::table('room_type')->where('name', $request['name'])->first();
        if (!$room) {
            return response([
                'message' => 'query not found'
            ]);
        }
        $imageUrl = asset('images/room_images/' . $room->icon_image);
        return response([
            'room' => $room,
            'URL' => $imageUrl
        ]);
    }

    function deleteRoom(Request $request)
    {

        $request->validate([
            'name' => 'required|max:50'
        ]);

        $room = DB::table('room_type')->where('name', $request['name'])->delete();
        if (!$room) {
            return response([
                'message' => 'delete failt'
            ]);
        }
        return response([
            'message' => 'succesfull'
        ]);
    }
}