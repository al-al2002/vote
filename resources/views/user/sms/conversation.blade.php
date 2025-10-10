@extends('layouts.user')

@section('title', 'Conversation')

@section('content')
    <div class="fixed inset-0 bg-black/40 flex items-start justify-center z-50 pt-16">
        <div class="bg-[#1E293B] w-full max-w-3xl mx-auto rounded-xl shadow-lg text-white flex flex-col h-[80vh]">

            {{-- Header --}}
            <div class="flex justify-between items-center p-4 border-b border-gray-600">
                <h2 class="text-lg font-semibold flex items-center gap-2">
                    💬 Conversation with Admin
                </h2>
                <div class="flex gap-2">
                    <a href="{{ route('user.messages.index') }}"
                        class="bg-gray-500 px-3 py-1 rounded-lg text-sm hover:bg-gray-600 transition">
                        ✖ Close
                    </a>
                </div>
            </div>

            {{-- Messages --}}
            <div id="messagesContainer"
                class="flex-1 overflow-y-auto px-4 py-3 space-y-4 scrollbar-thin scrollbar-thumb-gray-600 scrollbar-track-gray-800">
                @forelse ($messages as $msg)
                    <div class="flex {{ $msg->sender_type === 'user' ? 'justify-end' : 'justify-start' }} animate-fade-in">
                        <div
                            class="{{ $msg->sender_type === 'user' ? 'bg-blue-600' : 'bg-gray-700' }} px-4 py-2 max-w-[60%] flex flex-col gap-1 rounded-lg relative">
                            @if($msg->message)
                                <p>{{ $msg->message }}</p>
                            @endif
                            @if($msg->image)
                                <img src="{{ asset('storage/' . $msg->image) }}" class="rounded-lg max-w-full">
                            @endif
                            <span class="text-gray-300 text-xs self-end">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 mt-4">No messages yet.</p>
                @endforelse
            </div>

            {{-- Image Preview Area --}}
            <div id="previewContainer" class="flex gap-2 px-4 py-2 overflow-x-auto"></div>

            {{-- Reply form --}}
            <form id="replyForm" enctype="multipart/form-data"
                class="flex items-center gap-2 p-4 border-t border-gray-600 bg-[#1E293B]">
                @csrf
                <input type="text" name="message" placeholder="Type a message..."
                    class="flex-1 bg-gray-800 border border-gray-700 rounded-full px-4 py-2 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-500">

                {{-- Image icon --}}
                <label for="image" class="cursor-pointer text-gray-300 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3.5a2.5 2.5 0 115 0 2.5 2.5 0 01-5 0zM4 13l4-4 3 3 5-5 4 4" />
                    </svg>
                </label>
                <input type="file" name="image[]" id="image" multiple class="hidden">

                {{-- Send button --}}
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full text-sm transition flex items-center gap-1">
                    <span>Send</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Tailwind animation --}}
    <style>
        @keyframes fade-in-right {
            0% {
                opacity: 0;
                transform: translateX(50px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fade-in-left {
            0% {
                opacity: 0;
                transform: translateX(-50px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-right {
            animation: fade-in-right 0.3s ease-out;
        }

        .animate-fade-in-left {
            animation: fade-in-left 0.3s ease-out;
        }
    </style>

    <script>
        const form = document.getElementById('replyForm');
        const messagesContainer = document.getElementById('messagesContainer');
        const imageInput = document.getElementById('image');
        const previewContainer = document.getElementById('previewContainer');
        let selectedFiles = [];

        // Preview selected images with remove button
        imageInput.addEventListener('change', () => {
            previewContainer.innerHTML = '';
            selectedFiles = Array.from(imageInput.files);

            selectedFiles.forEach((file, index) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden border border-gray-600';

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'w-full h-full object-cover';
                wrapper.appendChild(img);

                // Remove button
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.innerHTML = '×';
                removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs';
                removeBtn.addEventListener('click', () => {
                    selectedFiles.splice(index, 1);
                    wrapper.remove();
                });
                wrapper.appendChild(removeBtn);

                previewContainer.appendChild(wrapper);
            });
        });

        // Handle send
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!form.message.value && selectedFiles.length === 0) return;

            const formData = new FormData(form);
            selectedFiles.forEach(file => formData.append('image[]', file));

            // Optimistic UI: show text bubble + images
            const bubble = document.createElement('div');
            bubble.className = 'flex justify-end animate-fade-in-right';
            let imagesHTML = '';
            selectedFiles.forEach(file => {
                imagesHTML += `<img src="${URL.createObjectURL(file)}" class="rounded-lg max-w-full mb-1">`;
            });
            bubble.innerHTML = `
            <div class="bg-blue-600 px-4 py-2 max-w-[60%] flex flex-col gap-1 rounded-lg relative">
                ${form.message.value ? `<p>${form.message.value}</p>` : ''}
                ${imagesHTML}
            </div>
        `;
            messagesContainer.appendChild(bubble);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            try {
                const response = await fetch('{{ route("user.messages.reply", $conversation_id) }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await response.json();
                if (!data.success) throw new Error('Upload failed');

                // Replace preview URLs with actual stored URLs
                const imgTags = bubble.querySelectorAll('img');
                data.messages.forEach((msg, i) => {
                    if (msg.image && imgTags[i]) {
                        imgTags[i].src = '{{ asset("storage") }}/' + msg.image;
                    }
                });

                form.reset();
                previewContainer.innerHTML = '';
                selectedFiles = [];
            } catch (err) {
                alert('Failed to send message.');
            }
        });
    </script>
@endsection
