@extends('layouts.user')

@section('title', 'New Message')

@section('content')
    {{-- Modal Container --}}
    <div class="fixed inset-0 flex items-start justify-center z-50 pt-16 pointer-events-none">
        <div class="w-full max-w-md rounded-xl text-white p-5 pointer-events-auto
                        bg-[#1E293B]/90 backdrop-blur-sm shadow-lg">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-4 border-b border-gray-600 pb-2">
                <h2 class="text-lg font-semibold">✉️ Chat with Admin</h2>
                <a href="{{ route('user.messages.index') }}" class="text-gray-400 hover:text-white text-sm font-medium">✖
                    Close</a>
            </div>

            {{-- Info Text --}}
            <div class="text-center text-gray-300 mb-4">
                @if($conversationId)
                    Continue your conversation with Admin.
                @else
                    Start a new conversation with Admin.
                @endif
            </div>

            {{-- Conversation / Form --}}
            <div class="text-center">
                @if($conversationId)
                    <a href="{{ route('user.messages.conversation', $conversationId) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                        Open Conversation
                    </a>
                @else
                    <form action="{{ route('user.messages.store') }}" method="POST" enctype="multipart/form-data"
                        class="flex flex-col gap-3">
                        @csrf
                        <textarea name="message" rows="4"
                            class="w-full bg-gray-800/80 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring focus:ring-blue-500"
                            placeholder="Write your message..." required></textarea>

                        <input type="file" name="image[]" multiple class="text-sm text-gray-300">

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                            Send
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>
@endsection
