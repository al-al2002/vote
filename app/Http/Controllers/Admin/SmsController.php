<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->latest()->get();
        return view('admin.sms.inbox', compact('messages'));
    }

    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);
        $message->status = 'read';
        $message->save();

        return redirect()->back()->with('success', 'Message marked as read.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $message = Message::findOrFail($id);
        // you could store replies in another table or email — here just store as reply column
        $message->reply = $request->reply;
        $message->status = 'read';
        $message->save();

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->back()->with('success', 'Message deleted successfully.');
    }
}
