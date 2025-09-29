@extends('layouts.admin')

@section('title', 'Election Results')

@section('content')
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-bold mb-6">Closed Election Results</h1>

        @forelse($elections as $election)
            <div class="bg-white p-6 rounded-lg shadow mb-6">

                {{-- Election Title --}}
                <h2 class="text-2xl font-bold mb-2 text-blue-600">{{ $election->title }}</h2>

                {{-- Status --}}
                <p>Status: <span class="text-red-600 font-semibold">Closed</span></p>

                {{-- Total Votes --}}
                @php
                    $totalVotes = $election->candidates->sum('votes_count');
                @endphp
                <p class="mt-1"><strong>Total Votes:</strong> {{ $totalVotes }}</p>

                {{-- Winners --}}
                @php
                    $winners = $election->winners(); // Returns all candidates with max votes
                @endphp
                <h4 class="mt-2 font-semibold">
                    Winner{{ $winners->count() > 1 ? 's (Tie)' : '' }}:
                </h4>
                @if($winners->isNotEmpty())
                    <ul class="list-disc list-inside text-yellow-600 font-bold">
                        @foreach($winners as $winner)
                            <li>🎉 {{ $winner->name }} ({{ $winner->votes_count }} votes)</li>
                        @endforeach
                    </ul>
                @else
                    <p>No votes were cast.</p>
                @endif

                {{-- Candidates Table --}}
                <h4 class="mt-4 font-semibold">Candidates:</h4>
                <table class="min-w-full mt-2 border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">Photo</th>
                            <th class="px-4 py-2 border">Name</th>
                            <th class="px-4 py-2 border">Votes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $winnerIds = $winners->pluck('id')->toArray(); @endphp
                        @foreach($election->candidates as $candidate)
                            <tr @if(in_array($candidate->id, $winnerIds)) class="bg-yellow-100 font-bold" @endif>
                                <td class="px-4 py-2 border">
                                    @if($candidate->photo)
                                        <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
                                            class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <span class="text-gray-400">No photo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border">{{ $candidate->name }}</td>
                                <td class="px-4 py-2 border">{{ $candidate->votes_count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <div class="bg-red-100 text-red-600 p-4 rounded-lg shadow text-center">
                <p>No closed elections found.</p>
            </div>
        @endforelse
    </div>
@endsection
