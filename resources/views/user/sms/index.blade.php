@extends('layouts.user')

@section('title', 'My Inbox')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg text-black relative">
        {{-- Close Button --}}
        <a href="{{ route('user.dashboard') }}"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 font-bold text-lg">
            ×
        </a>

        <h2 class="text-xl font-bold mb-4">📥 My Inbox</h2>

        {{-- SweetAlert Notifications --}}
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 1800,
                    position: 'top-end',
                    toast: true
                });
            </script>
        @endif

        {{-- Create Message --}}
        <div class="mb-6">
            <form action="{{ route('user.messages.store') }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                @csrf
                <input type="hidden" name="to" value="admin">
                <input type="text" name="message" placeholder="Type your message to admin..." required
                    class="flex-1 px-3 py-2 border rounded text-sm focus:ring-blue-400 focus:border-blue-400">
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 font-semibold">
                    Send
                </button>
            </form>
        </div>

        {{-- Inbox Table --}}
        @if($messages->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Message</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($messages as $message)
                            <tr>
                                {{-- Message + Admin Reply --}}
                                <td class="px-4 py-2 flex flex-col gap-1">
                                    <div class="flex items-start gap-2">
                                        @if($message->user_status === 'unread')
                                            <span class="w-3 h-3 bg-red-500 rounded-full mt-1 inline-block"></span>
                                        @endif
                                        <div>{{ $message->message }}</div>
                                    </div>

                                    {{-- Admin Reply --}}
                                    @if($message->reply)
                                        <div class="mt-1 px-2 py-1 bg-blue-50 text-blue-800 rounded text-sm">
                                            <strong>Admin Reply:</strong> {{ $message->reply }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-2">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full
                                                {{ $message->user_status === 'unread' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                        {{ ucfirst($message->user_status) }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-2 text-center space-x-2">
                                    {{-- Reply --}}
                                    <form action="{{ route('user.messages.reply', $message->id) }}" method="POST"
                                        class="inline-flex gap-1">
                                        @csrf
                                        <input type="text" name="message" placeholder="Type reply..." required
                                            class="px-2 py-1 border rounded text-sm focus:ring-blue-400 focus:border-blue-400">
                                        <button type="submit"
                                            class="px-2 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                            Reply
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form action="{{ route('user.messages.delete', $message->id) }}" method="POST"
                                        class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-2 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 rounded">
                <p class="text-sm">No messages yet.</p>
            </div>
        @endif
    </div>

    {{-- SweetAlert Delete Confirmation --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.delete-form').forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This message will be deleted permanently!",
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
                });
            });
        });
    </script>
@endsection
