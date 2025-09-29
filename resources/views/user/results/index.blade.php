@extends('layouts.user')

@section('title', 'Election Results')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-yellow-400">Election Results</h1>

            {{-- Back to Dashboard --}}
            <a href="{{ route('user.dashboard') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                ← Back to Dashboard
            </a>
        </div>

        @php
            // Filter only closed elections
            $closedElections = $elections->filter(fn($e) => $e->isClosed());
        @endphp

        @forelse($closedElections as $election)
            <div class="card mb-6 p-4 rounded-lg shadow bg-[#10243F] text-white">
                {{-- Election Title --}}
                <h2 class="text-xl font-bold text-blue-400 mb-2">{{ $election->title }}</h2>

                {{-- Election Status --}}
                <p class="mb-2">
                    Status:
                    <span class="status-badge status-closed">Closed</span>
                </p>

                {{-- Total Votes --}}
                <p class="mb-2"><strong>Total Votes:</strong> {{ $election->total_votes ?? 0 }}</p>

                {{-- Winners --}}
                @php
                    $maxVotes = $election->candidates->max('votes_count');
                    $winners = $election->candidates->where('votes_count', $maxVotes);
                @endphp

                <h4 class="font-semibold mt-2">
                    Winner{{ $winners->count() > 1 ? 's (Tie)' : '' }}:
                </h4>

                @if($winners->count() > 0)
                    <ul class="list-disc list-inside text-yellow-400 font-bold">
                        @foreach($winners as $winner)
                            <li>🎉 {{ $winner->name }} ({{ $winner->votes_count }} votes)</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-400">No votes were cast.</p>
                @endif

                {{-- Candidates Table --}}
                <h4 class="mt-4 font-semibold">Candidates:</h4>
                <table class="min-w-full mt-2 border border-gray-700 text-white">
                    <thead>
                        <tr class="bg-gray-800">
                            <th class="px-4 py-2 border border-gray-700">Photo</th>
                            <th class="px-4 py-2 border border-gray-700">Name</th>
                            <th class="px-4 py-2 border border-gray-700">Votes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($election->candidates as $candidate)
                            <tr @if($winners->contains('id', $candidate->id)) class="bg-yellow-900 font-bold" @endif>
                                <td class="px-4 py-2 border border-gray-700">
                                    @if($candidate->photo)
                                        <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
                                            class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <span class="text-gray-400">No photo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border border-gray-700">{{ $candidate->name }}</td>
                                <td class="px-4 py-2 border border-gray-700">{{ $candidate->votes_count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-gray-400">No closed election results available.</p>
        @endforelse
    </div>
@endsection
