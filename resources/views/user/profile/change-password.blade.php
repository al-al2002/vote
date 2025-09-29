@extends('layouts.user')

@section('title', 'Change Password')

@section('content')
    <div class="max-w-xl mx-auto bg-[#10243F] p-6 rounded-lg shadow border border-gray-700 text-white">

        {{-- Back Arrow --}}
        <a href="{{ route('user.dashboard') }}" class="flex items-center text-gray-300 mb-4 hover:text-white">
            <span class="mr-2">←</span> Back
        </a>

        <h2 class="text-xl font-bold mb-4">Change Password</h2>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-600 text-white rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-600 text-white rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Change Password Form --}}
        <form method="POST" action="{{ route('user.password.update') }}">
            @csrf

            {{-- Current Password --}}
            <div class="mb-4 relative">
                <label for="current_password" class="block font-medium">Current Password</label>
                <input type="password" name="current_password" id="current_password"
                    class="w-full border border-gray-600 bg-gray-800 text-white px-3 py-2 rounded pr-10" required>
                <button type="button" onclick="togglePassword('current_password', this)"
                    class="absolute right-3 top-9 text-gray-400 hover:text-white">👁</button>
            </div>

            {{-- New Password --}}
            <div class="mb-4 relative">
                <label for="new_password" class="block font-medium">New Password</label>
                <input type="password" name="new_password" id="new_password"
                    class="w-full border border-gray-600 bg-gray-800 text-white px-3 py-2 rounded pr-10" required>
                <button type="button" onclick="togglePassword('new_password', this)"
                    class="absolute right-3 top-9 text-gray-400 hover:text-white">👁</button>
            </div>

            {{-- Confirm New Password --}}
            <div class="mb-4 relative">
                <label for="new_password_confirmation" class="block font-medium">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                    class="w-full border border-gray-600 bg-gray-800 text-white px-3 py-2 rounded pr-10" required>
                <button type="button" onclick="togglePassword('new_password_confirmation', this)"
                    class="absolute right-3 top-9 text-gray-400 hover:text-white">👁</button>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Update Password
            </button>
        </form>
    </div>

    {{-- Toggle password visibility --}}
    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            if (input.type === "password") {
                input.type = "text";
                btn.textContent = "🙈";
            } else {
                input.type = "password";
                btn.textContent = "👁";
            }
        }
    </script>
@endsection
