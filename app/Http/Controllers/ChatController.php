<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Events\ChatEvent;
use App\Models\ChatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ChatController extends Controller
{
    public function getAllChatUser(Request $request)
    {
        $user = $request->user();

        DB::statement("SET SQL_MODE=''");
        $results = DB::table('tp_messages')
            ->join(DB::raw('(SELECT MAX(id) as id, chanel_name FROM tp_messages GROUP BY chanel_name) as new'), function ($join) {
                $join->on('tp_messages.id', '=', 'new.id');
            })
            ->join('users as from_user', 'tp_messages.from_email', '=', 'from_user.email')
            ->join('users as to_user', 'tp_messages.to_email', '=', 'to_user.email')
            ->select('tp_messages.*', 'from_user.image as from_user_image', 'to_user.image as to_user_image')
            ->where('from_email', $user->email)
            ->orWhere('to_email', $user->email)
            ->get();



        foreach ($results as $result) {
            if ($result->from_user_image) {
                $result->from_user_image = asset('storage/images/users/' . $result->from_user_image);
            }

            if ($result->to_user_image) {
                $result->to_user_image = asset('storage/images/users/' . $result->to_user_image);
            }
        }

        return response($results);
    }

    public function sendMessage(Request $request)
    {
        $channel_name = [$request->user()->email, $request->email];
        sort($channel_name);

        $channel_name = join("-", $channel_name);

        event(new ChatEvent($request->user()->email, $request->email, $request->message,  $channel_name));

        $chat = new Chat();
        $chat->from_email = $request->user()->email;
        $chat->to_email = $request->email;
        $chat->body  = $request->message;
        $chat->chanel_name = $channel_name;
        $chat->save();

        $channel = Chat::where('chanel_name', $channel_name)->get();

        if (count($channel) == 1) {
            event(new ChatEvent($request->email, $request->email, "new user",  $channel_name));
        }
    }

    public function getMessage(Request $request)
    {
        $messages = Chat::where('chanel_name', $request->channel)->get();

        return response($messages);
    }
}
