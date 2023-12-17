<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Events\ChatEvent;
use App\Models\ChatModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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
        $user = $request->user();
        $user_from_email = $user->email;
        $user_to_email = $request->user_to_email;
        $emails = [$user_from_email, $user_to_email];

        $messages = DB::table('tp_messages')
            ->where(function ($query) use ($emails) {
                $query->whereIn('from_email', $emails)
                    ->WhereIn('to_email', $emails);
            })
            ->get();
        return $messages;
    }

    function getAllUser(Request $request)
    {
        $AllUser = [];

        DB::statement("SET SQL_MODE=''");
        $user = $request->user();
        $fromEmail = $user->email;
        $array1 = DB::table('tp_messages')
            ->select('tp_messages.*')
            ->where('from_email', $fromEmail)
            ->orWhere('to_email', $fromEmail)
            ->groupBy('from_email')
            ->pluck('from_email');
        $array2 = DB::table('tp_messages')
            ->select('tp_messages.*')
            ->where('from_email', $fromEmail)
            ->orWhere('to_email', $fromEmail)
            ->groupBy('to_email')
            ->pluck('to_email');

        foreach ($array1 as $key => $value) {
            if (!in_array($value, $AllUser) && $value != $fromEmail) {
                $AllUser[] = $value;
            }
        }
        foreach ($array2 as $key => $value) {
            if (!in_array($value, $AllUser) && $value != $fromEmail) {
                $AllUser[] = $value;
            }
        }

        $users = DB::table('users')
            ->whereIn('email', $AllUser)
            ->get();
        return $users;
    }
}
