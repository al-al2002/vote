@extends('layouts.user')

@section('title', 'Profile Settings')

@section('content')
    <div class="max-w-xl mx-auto bg-[#10243F] p-6 rounded-lg shadow border border-gray-700 text-white">
        {{-- Back Arrow --}}
        <a href="{{ route('user.dashboard') }}" class="flex items-center text-gray-300 mb-4 hover:text-white">
            ← Back
        </a>

        <h2 class="text-xl font-bold mb-4">Profile Settings</h2>

        <p class="text-gray-300">Here you can manage extra profile settings.</p>
    </div>
@endsection
