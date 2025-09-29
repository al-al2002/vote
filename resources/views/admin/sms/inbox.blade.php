@extends('layouts.admin')

@section('title', 'Admin Inbox')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">📥 Inbox (Messages from Voters)</h2>

        {{-- ✅ SweetAlert on success --}}
        @if(session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function () {
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
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Voter ID</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Message</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($messages as $message)
                            <tr class="{{ $message->status === 'unread' ? 'bg-yellow-50' : '' }}">
                                <td class="px-4 py-2">{{ $message->user->voter_id }}</td>
                                <td class="px-4 py-2 font-medium text-gray-800">{{ $message->user->name }}</td>

                                {{-- ✅ Truncate long messages --}}
                                <td class="px-4 py-2 text-gray-600">
                                    @if(strlen($message->message) > 50)
                                        {{ Str::limit($message->message, 50) }}
                                        <a href="#" class="text-blue-600 hover:underline"
                                            onclick="Swal.fire({ title: 'Full Message', text: '{{ addslashes($message->message) }}', confirmButtonColor: '#3085d6' })">
                                            View more
                                        </a>
                                    @else
                                        {{ $message->message }}
                                    @endif
                                </td>

                                <td class="px-4 py-2">
                                    @if($message->status === 'unread')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Unread</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Read</span>
                                    @endif
                                </td>

                                <td class="px-4 py-2 text-center flex items-center justify-center gap-2">
                                    {{-- ✅ Mark as Read --}}
                                    @if($message->status === 'unread')
                                        <form action="{{ route('admin.sms.read', $message->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1 text-sm rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif

                                    {{-- ✅ Reply --}}
                                    <button onclick="openReplyModal({{ $message->id }}, '{{ addslashes($message->user->name) }}')"
                                        class="px-3 py-1 text-sm rounded-lg bg-green-500 text-white hover:bg-green-600">
                                        Reply
                                    </button>

                                    {{-- ✅ Delete --}}
                                    <form action="{{ route('admin.sms.delete', $message->id) }}" method="POST"
                                        class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="px-3 py-1 text-sm rounded-lg bg-red-500 text-white hover:bg-red-600"
                                            onclick="confirmDelete(this)">
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
                <p class="text-sm">No messages received yet.</p>
            </div>
        @endif
    </div>

    {{-- ✅ Reply Modal --}}
    <div id="replyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <h3 class="text-lg font-semibold mb-4">Reply to <span id="replyUser"></span></h3>
            <form id="replyForm" method="POST">
                @csrf
                <textarea name="reply" rows="4" class="w-full border rounded-lg p-2 mb-4" placeholder="Write your reply..."
                    required></textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeReplyModal()" class="px-4 py-2 rounded bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Send Reply</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ✅ Reply Modal functions
        function openReplyModal(id, name) {
            document.getElementById('replyModal').classList.remove('hidden');
            document.getElementById('replyModal').classList.add('flex');
            document.getElementById('replyUser').innerText = name;
            document.getElementById('replyForm').action = '/admin/sms/reply/' + id;
        }

        function closeReplyModal() {
            document.getElementById('replyModal').classList.add('hidden');
        }

        // ✅ Delete Confirmation
        function confirmDelete(button) {
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
                    button.closest('form').submit();
                }
            });
        }
    </script>
@endsection
