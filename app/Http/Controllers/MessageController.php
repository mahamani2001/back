<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MessageController extends Controller
{

    public function store(Request $request)
    {
        $message = new Message();
        $message->name = $request->input('name');
        $message->message = $request->input('message');
        $message->emoji = $request->input('emoji');
        $message->save();

        return response()->json(['message' => 'Message sent successfully'], 200);
    }
    public function sendToJobber(Request $request)
    {
        $validatedData = $request->validate([
            'jobber_id' => 'required|exists:users,id',
            'text_message' => 'required',
            'vu' => 'boolean'
        ]);
        $message = new Message();
        $message->user_id = auth()->user()->id;
        $message->jobber_id = $validatedData['jobber_id'];
        $message->text_message = $validatedData['text_message'];
        $message->vu_message = $validatedData['vu'] ?? false; // set default to false if not provided
        $message->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully!'
        ]);
    }
    public function sendToClient(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'text_message' => 'required',
            'vu' => 'boolean'
        ]);
        $message = new Message();
        $message->jobber_id = auth()->user()->id;
        $message->user_id = $validatedData['user_id'];
        $message->text_message = $validatedData['text_message'];
        $message->vu_message = $validatedData['vu'] ?? false; // set default to false if not provided
        $message->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully!'
        ]);
    }
    public function index()
    {
        $user = auth()->user();
    
        $sent_messages = $user->sentMessages()->with('jobber')->get();
        $received_messages = $user->receivedMessages()->with('user')->get();
    
        $messages = [
            'sent' => $sent_messages,
            'received' => $received_messages
        ];
    
        return response()->json($messages);
    }
    public function jobbermessage(Request $request)
    {
        $user = $request->user();
        $sent_messages = $user->messages()->with('user')->where('jobber_id', $user->id)->get();
        $received_messages = $user->receivedMessages()->with('user')->where('user_id', $user->id)->get();
        $messages = [
            'sent' => $sent_messages,
            'received' => $received_messages
        ];
        return response()->json($messages);
    }

}
