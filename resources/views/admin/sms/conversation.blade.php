@extends('layouts.admin')

@section('title', 'Conversation')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">💬 Conversation</h2>

        <div class="space-y-4 mb-6 max-h-[400px] overflow-y-auto">
            @foreach($messages as $message)
                <div class="p-3 rounded-lg {{ $message->sender_type === 'admin' ? 'bg-blue-100 text-right' : 'bg-gray-100' }}">
                    <strong>{{ $message->sender_type === 'admin' ? 'Admin' : $message->user->name }}</strong>:
                    <p>{{ $message->message }}</p>
                    <small class="text-gray-500">{{ $message->created_at->format('d M Y H:i') }}</small>
                </div>
            @endforeach
        </div>

        <form action="{{ route('admin.sms.reply', $conversation_id) }}" method="POST">
            @csrf
            <textarea name="reply" rows="3" class="w-full border rounded-lg p-2 mb-4" placeholder="Write your reply..."
                required></textarea>
            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Send Reply</button>
        </form>
    </div>
@endsection
