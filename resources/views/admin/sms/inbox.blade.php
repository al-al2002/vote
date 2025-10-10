@extends('layouts.admin')

@section('title', 'Admin Inbox')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">📥 Inbox (Messages from Voters)</h2>

        @if(session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session("success") }}',
                        confirmButtonColor: '#3085d6',
                    });
                });
            </script>
        @endif

        @if($messages->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Sender</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Message</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($messages as $message)
                            <tr
                                class="{{ $message->status === 'unread' && $message->sender_type === 'user' ? 'bg-yellow-50' : '' }}">
                                <td class="px-4 py-2">
                                    {{ $message->user->name }} ({{ $message->user->voter_id }})
                                </td>
                                <td class="px-4 py-2 text-gray-600">
                                    {{ Str::limit($message->message, 50) }}
                                </td>
                                <td class="px-4 py-2">
                                    @if($message->status === 'unread' && $message->sender_type === 'user')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Unread</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Read</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.sms.conversation', $message->conversation_id) }}"
                                        class="px-3 py-1 text-sm rounded-lg bg-green-500 text-white hover:bg-green-600">
                                        View Conversation
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 rounded">
                <p class="text-sm">No messages received yet.</p>
            </div>
        @endif
    </div>
@endsection
