<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Show inbox
    public function index()
    {
        $messages = Message::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Make sure the Blade path is correct: resources/views/user/sms/index.blade.php
        return view('user.sms.index', compact('messages'));
    }

    // Send new message to admin
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'to' => 'admin', // ensure you have a 'to' column in messages table
            'message' => $request->message,
            'status'  => 'unread',
        ]);

        return redirect()->route('user.messages.index')->with('success', 'Message sent to admin!');
    }

    // Reply to a message
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'user_id' => Auth::id(),
            'to' => 'admin', // always reply to admin
            'message' => $request->message,
            'status'  => 'unread',
            'reply_to' => $id, // reference original message
        ]);

        return redirect()->route('user.messages.index')->with('success', 'Reply sent successfully!');
    }

    // Delete message
    public function destroy($id)
    {
        $message = Message::where('user_id', Auth::id())->findOrFail($id);
        $message->delete();

        return redirect()->route('user.messages.index')->with('success', 'Message deleted.');
    }
}
