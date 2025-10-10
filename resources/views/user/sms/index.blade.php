@extends('layouts.user')

@section('title', 'Inbox')

@section('content')
    <div class="max-w-md mx-auto mt-10 rounded-xl shadow-lg text-white p-5 bg-[#1E293B]">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-4 border-b border-gray-600 pb-2">
            <h2 class="text-lg font-semibold">📥 Inbox</h2>

            <div class="flex gap-2">
                {{-- New Message --}}
                <a href="{{ route('user.messages.create') }}"
                    class="bg-blue-600 px-3 py-1 rounded-lg text-sm hover:bg-blue-700 transition">
                    + New
                </a>
                {{-- Close / Back to Dashboard --}}
                <a href="{{ route('user.dashboard') }}"
                    class="bg-gray-500 px-3 py-1 rounded-lg text-sm hover:bg-gray-600 transition">
                    ✖ Close
                </a>
            </div>
        </div>

        {{-- Conversations list --}}
        @forelse($messages as $conversation)
            <div class="bg-gray-700 hover:bg-gray-600 p-3 rounded-lg transition flex flex-col gap-2 mb-2">

                <a href="{{ route('user.messages.conversation', $conversation->conversation_id) }}"
                    class="flex items-center gap-3">
                    {{-- Admin avatar --}}
                    <div class="flex-shrink-0">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                            A
                        </div>
                    </div>

                    {{-- Message preview and name --}}
                    <div class="flex-1 flex flex-col">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-sm">To:Admin</span>
                            <small class="text-gray-400 text-xs">{{ $conversation->latest_time->diffForHumans() }}</small>
                        </div>
                        <p class="truncate text-gray-200 text-sm mt-1">
                            @if($conversation->latest_message)
                                {{ $conversation->latest_message }}
                            @elseif($conversation->latest_image)
                                <span class="italic text-gray-400">Image message</span>
                            @else
                                <span class="italic text-gray-400">No content</span>
                            @endif
                        </p>
                    </div>

                    {{-- Unread badge --}}
                    @if($conversation->unread_count > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
                            {{ $conversation->unread_count }}
                        </span>
                    @endif
                </a>

                {{-- Delete button --}}
                <form action="{{ route('user.messages.destroy', $conversation->conversation_id) }}" method="POST"
                    class="mt-2 text-right" onsubmit="return confirm('Are you sure you want to delete this conversation?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm transition">
                        🗑 Delete Conversation
                    </button>
                </form>
            </div>
        @empty
            <p class="text-center text-gray-400 mt-4">No messages yet. Click "New" to chat with Admin.</p>
        @endforelse
    </div>
@endsection
