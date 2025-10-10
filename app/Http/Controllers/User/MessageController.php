<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Message;

class MessageController extends Controller
{
   // Show all user conversations
public function index()
{
    $userType = 'user';

    // Get all messages where sender or receiver involves user
    $messages = Message::where(function($q) use ($userType) {
            $q->where('sender_type', $userType)
              ->orWhere('to', $userType);
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('conversation_id')
        ->map(function ($conversationMessages) use ($userType) {
            $latest = $conversationMessages->sortByDesc('created_at')->first();

            $unreadCount = $conversationMessages->where('to', $userType)
                                                ->where('status', 'unread')
                                                ->count();

            return (object)[
                'conversation_id' => $latest->conversation_id,
                'latest_message' => $latest->message,
                'latest_image' => $latest->image,
                'latest_time' => $latest->created_at,
                'unread_count' => $unreadCount,
            ];
        })
        ->values(); // reset keys

    return view('user.sms.index', compact('messages'));
}


    // Show form to create a new message
    public function create()
    {
        // Pass existing conversationId if exists
        $userType = 'user';
        $admin = 'admin';

        $existingConversation = Message::where(function ($q) use ($userType, $admin) {
                $q->where('sender_type', $userType)->where('to', $admin);
            })
            ->orWhere(function ($q) use ($userType, $admin) {
                $q->where('sender_type', $admin)->where('to', $userType);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        $conversationId = $existingConversation ? $existingConversation->conversation_id : null;

        return view('user.sms.create', compact('conversationId'));
    }

    // Store new message
 public function store(Request $request)
{
    $request->validate([
        'message' => 'nullable|string|max:1000',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $path = $request->hasFile('image') ? $request->file('image')->store('messages', 'public') : null;
    $userId = Auth::id();

    // Always create new conversation
    $conversationId = (string) Str::uuid();

    Message::create([
        'conversation_id' => $conversationId,
        'user_id' => $userId,
        // Set empty string if null
        'message' => $request->message ?? '',
        'image' => $path,
        'status' => 'unread',
        'to' => 'admin',
        'sender_type' => 'user',
    ]);

    return redirect()->route('user.messages.conversation', $conversationId)->with('success', 'Message sent successfully!');
}


    // Show conversation thread
    public function conversation($conversation_id)
    {
        $messages = Message::where('conversation_id', $conversation_id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages to user as read
        Message::where('conversation_id', $conversation_id)
            ->where('to', 'user')
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return view('user.sms.conversation', compact('messages', 'conversation_id'));
    }

    // Reply to conversation
public function reply(Request $request, $conversation_id)
{
    $request->validate([
        'message' => 'nullable|string|max:1000',
        'image.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // allow multiple images
    ]);

    $lastMessage = Message::where('conversation_id', $conversation_id)->latest()->first();
    $to = $lastMessage && $lastMessage->sender_type === 'user' ? 'admin' : 'user';

    $messagesCreated = [];

    // Handle multiple images
    if ($request->hasFile('image')) {
        foreach ($request->file('image') as $img) {
            $path = $img->store('messages', 'public');

            $message = Message::create([
                'conversation_id' => $conversation_id,
                'user_id' => Auth::id(),
                'message' => $request->message ?? '',
                'image' => $path,
                'status' => 'unread',
                'to' => $to,
                'sender_type' => 'user',
            ]);

            $messagesCreated[] = $message;
        }
    }

    // If text only and no image
    if (!$request->hasFile('image') && $request->message) {
        $message = Message::create([
            'conversation_id' => $conversation_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'image' => null,
            'status' => 'unread',
            'to' => $to,
            'sender_type' => 'user',
        ]);

        $messagesCreated[] = $message;
    }

    // Return JSON for AJAX support
    return response()->json([
        'success' => true,
        'messages' => $messagesCreated,
    ]);
}


    // Delete entire conversation
   public function destroy($conversation_id)
{
    Message::where('conversation_id', $conversation_id)->delete();

    return redirect()->route('user.messages.index')->with('success', 'Conversation deleted successfully!');
}
}
