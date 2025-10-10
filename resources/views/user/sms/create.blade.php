@extends('layouts.user')

@section('title', 'New Message')

@section('content')
    <div class="fixed inset-0 bg-black/60 flex items-start justify-center z-50 pt-16">
        <div class="bg-[#1E293B] w-full max-w-md mx-auto rounded-xl shadow-lg text-white p-5">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-4 border-b border-gray-600 pb-2">
                <h2 class="text-lg font-semibold">✉️ Chat with Admin</h2>
                <a href="{{ route('user.messages.index') }}" class="text-gray-400 hover:text-white text-sm font-medium">✖
                    Close</a>
            </div>

            <div class="text-center text-gray-300 mb-4">
                Continue your conversation with Admin.
            </div>

            <div class="text-center">
                @if($conversationId)
                    <a href="{{ route('user.messages.conversation', $conversationId) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                        Open Conversation
                    </a>
                @else
                    <p class="text-gray-400">No conversation found. Sending a message will create one.</p>
                    <form action="{{ route('user.messages.store') }}" method="POST" enctype="multipart/form-data"
                        class="mt-3 flex flex-col gap-2">
                        @csrf
                        <textarea name="message" rows="4"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring focus:ring-blue-500"
                            placeholder="Write your message..." required></textarea>
                        <input type="file" name="image" class="text-sm text-gray-300">
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
