<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        return Message::all();
    }

    public function store(Request $request)
    {
        $message = new Message();
        $message->name = $request->input('name');
        $message->message = $request->input('message');
        $message->emoji = $request->input('emoji');
        $message->save();

        return response()->json(['message' => 'Message sent successfully'], 200);
    }
}
