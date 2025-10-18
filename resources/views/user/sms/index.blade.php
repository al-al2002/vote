@extends('layouts.user')

@section('title', 'Inbox')

@section('content')
    <div class="w-full max-w-md mx-auto mt-16 rounded-xl shadow-lg text-white p-5 bg-[#1E293B]">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-4 border-b border-gray-600 pb-2">
            <h2 class="text-xl font-semibold flex items-center gap-2">📥 Inbox</h2>
            <div class="flex gap-2">
                <a href="{{ route('user.messages.create') }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-yellow-900 px-3 py-1 rounded-lg text-sm font-semibold transition">
                    + New
                </a>
                <a href="{{ route('user.dashboard') }}"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm transition">
                    ✖ Close
                </a>
            </div>
        </div>

        {{-- Conversations List --}}
        <div class="space-y-4">
            @forelse ($messages as $conversation)
                @php
                    $unread = $conversation->unread_count_admin ?? 0;
                    $latestImages = !empty($conversation->latest_image) ? json_decode($conversation->latest_image, true) : [];
                @endphp

                <div class="bg-gray-800 p-4 rounded-lg flex flex-col gap-2">
                    <div class="flex justify-between items-start">
                        <div class="flex flex-col gap-1">
                            <p class="text-sm text-gray-400">To: Admin</p>

                            {{-- Latest message --}}
                            <p class="font-semibold flex items-center gap-2">
                                @if($conversation->sender_type === 'admin')
                                    Admin: {{ $conversation->latest_message ?? '[No message]' }}
                                @else
                                    You: {{ $conversation->latest_message ?? '[No message]' }}
                                @endif

                                {{-- Unread badge --}}
                                @if($unread > 0)
                                    <span class="bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">
                                        {{ $unread }}
                                    </span>
                                @endif
                            </p>

                            {{-- Latest images preview --}}
                            @if(!empty($latestImages) && is_array($latestImages))
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach($latestImages as $img)
                                        <img src="{{ asset('storage/' . $img) }}" class="rounded-lg max-w-full h-20 object-cover">
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <span class="text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($conversation->latest_time)->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-between items-center mt-3">
                        <a href="{{ route('user.messages.conversation', $conversation->conversation_id) }}"
                            class="text-blue-400 hover:underline text-sm">Open Conversation</a>

                        <form action="{{ route('user.messages.destroyConversation', $conversation->conversation_id) }}"
                            method="POST" class="delete-conversation">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-xs bg-red-600 hover:bg-red-700 px-2 py-1 rounded-lg text-white transition">
                                🗑 Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-center mt-4 text-sm">No conversations found.</p>
            @endforelse
        </div>
    </div>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Delete confirmation
            document.body.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.classList.contains('delete-conversation')) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This will remove the conversation from your inbox!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });

            // Success toast
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ session("success") }}',
                    toast: true,
                    position: 'top-end',
                    timer: 1500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    width: 250,
                    padding: '0.5em',
                    fontSize: '0.875rem'
                });
            @endif
    });
    </script>
@endsection
