<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Events\ChatEvent;
use App\Models\ChatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    function sendMessage(Request $request)
    {
        $user1 = $request->user()->email;
        $user2 = $request->user2;
        $message = $request->message;

        event(new ChatEvent($user1, $user2, $message));

        $chat = new Chat();
        $chat->from_email = $user1;
        $chat->to_email = $user2;
        $chat->body = $message;
        $chat->save();

        return response($chat);
    }

    function getMessage(Request $request)
    {
        $user1 = $request->user1;
        $user2 = $request->user2;
        $query = DB::table('tp_messages')
            ->where(function ($query) use ($user1, $user2) {
                $query->where('from_email', $user1)
                    ->orWhere('from_email', $user2);
            })
            ->where(function ($query) use ($user1, $user2) {
                $query->where('to_email', $user1)
                    ->orWhere('to_email', $user2);
            })
            ->get();

        return $query;
    }

    function getAllUser(Request $request)
    {
        DB::statement("SET SQL_MODE=''");
        $fromEmail = $request['fromEmail'];
        $rs = DB::table('tp_messages')
            ->select('*')
            ->where('from_email', $fromEmail)
            ->orWhere('to_email', $fromEmail)
            ->groupBy('to_email')
            ->get();

        return $rs;
    }
}
