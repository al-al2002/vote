@extends('layouts.user')

@section('title', 'Live Monitor')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-yellow-400" >Live Monitor</h1>
            <a href="{{ route('user.dashboard') }}"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                &times; Close
            </a>

        </div>

        @forelse($activeElections as $election)
            <div class="bg-[#10243F] border border-gray-700 rounded-lg p-6 mb-6">
                <p class="text-lg font-semibold mb-4">
                    Active Election: <span class="text-yellow-400">{{ $election->title }}</span>
                </p>

                <h2 class="text-xl font-bold mb-2">Candidates & Live Votes</h2>
                <ul class="space-y-2">
                    @foreach($election->candidates as $candidate)
                        <li class="flex justify-between p-3 border border-gray-600 rounded bg-[#09182D]">
                            <div class="flex items-center space-x-3">
                                @if($candidate->photo)
                                    <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
                                        class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center text-gray-300">
                                        N/A
                                    </div>
                                @endif
                                <span>{{ $candidate->name }}</span>
                            </div>
                            <span class="font-bold text-yellow-400">{{ $candidate->votes_count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p class="text-gray-400">No active elections at the moment.</p>
        @endforelse
    </div>
@endsection
