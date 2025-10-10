<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    // Show inbox: latest message per conversation
    public function index()
    {
        $messages = Message::with('user')
            ->latest()
            ->get()
            ->groupBy('conversation_id')
            ->map(fn($msgs) => $msgs->first());

        return view('admin.sms.inbox', compact('messages'));
    }

    // Show full conversation
    public function conversation($conversation_id)
    {
        $messages = Message::with('user')
            ->where('conversation_id', $conversation_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.sms.conversation', compact('messages', 'conversation_id'));
    }

    // Mark message as read
    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);
        $message->status = 'read';
        $message->save();

        return redirect()->back()->with('success', 'Message marked as read.');
    }

    // Reply to a conversation
    public function reply(Request $request, $conversation_id)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        // Get the original conversation's user_id
        $firstMessage = Message::where('conversation_id', $conversation_id)->first();

        if (!$firstMessage) {
            return redirect()->back()->with('error', 'Conversation not found.');
        }

        // Create admin reply
        Message::create([
            'user_id' => $firstMessage->user_id, // voter receiving the reply
            'conversation_id' => $conversation_id,
            'message' => $request->reply,
            'status' => 'unread',
            'sender_type' => 'admin',
        ]);

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }

    // Delete a message
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->back()->with('success', 'Message deleted successfully.');
    }
}
