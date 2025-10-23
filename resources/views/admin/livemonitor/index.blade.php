@extends('layouts.admin')

@section('title', 'Live Monitor')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold mb-6">Live Monitor</h1>

        @if($activeElections->count() > 0)
            @foreach($activeElections as $election)
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    {{-- Election Title --}}
                    <p class="text-lg font-semibold mb-4">
                        Active Election: <span class="text-blue-600">{{ $election->title }}</span>
                    </p>

                    @php
                        // ✅ Group candidates by position
                        $groupedCandidates = $election->candidates->groupBy('position');
                    @endphp

                    @foreach($groupedCandidates as $position => $candidates)
                        {{-- Position Header --}}
                        <h2 class="text-xl font-bold text-blue-500 mb-3 mt-6 border-b border-gray-300 pb-1">
                            {{ ucfirst($position) ?? 'Unknown Position' }}
                        </h2>

                        {{-- Sort by highest votes --}}
                        @php
                            $sortedCandidates = $candidates->sortByDesc('votes_count');
                        @endphp

                        <ul class="space-y-2">
                            @foreach($sortedCandidates as $candidate)
                                <li class="p-3 border rounded bg-gray-50 hover:bg-gray-100 transition">
                                    <div class="flex items-center justify-between">
                                        {{-- Candidate info --}}
                                        <div class="flex items-center space-x-3">
                                            @if($candidate->photo)
                                                <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
                                                    class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                                                    ?
                                                </div>
                                            @endif
                                            <span class="font-semibold">{{ $candidate->name }}</span>
                                        </div>

                                        {{-- Votes count + dropdown toggle --}}
                                        <div class="flex items-center space-x-4">
                                            <span class="font-bold text-blue-600 text-lg">{{ $candidate->votes_count ?? 0 }}</span>
                                            <button type="button"
                                                class="text-sm bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded toggle-voters"
                                                data-target="voters-{{ $candidate->id }}">
                                                Show Voters
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Hidden voters list --}}
                                    <div id="voters-{{ $candidate->id }}" class="hidden mt-3">
                                        @if($candidate->votes->count() > 0)
                                            <ul class="pl-6 space-y-2">
                                                @foreach($candidate->votes as $vote)
                                                    @if($vote->user)
                                                        <li class="flex items-center space-x-3 border-b pb-2">
                                                            {{-- Voter Photo --}}
                                                            <div class="w-8 h-8 rounded-full overflow-hidden border">
                                                                @if($vote->user->profile_photo)
                                                                    <img src="{{ asset('storage/' . $vote->user->profile_photo) }}"
                                                                        alt="{{ $vote->user->name }}" class="w-full h-full object-cover">
                                                                @else
                                                                    <div
                                                                        class="w-full h-full flex items-center justify-center bg-gray-400 text-white text-xs">
                                                                        {{ strtoupper(substr($vote->user->name, 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            {{-- Voter Info --}}
                                                            <span class="text-sm text-gray-700">
                                                                <strong>Voter ID:</strong> {{ $vote->user->voter_id ?? 'N/A' }} –
                                                                {{ $vote->user->name }}
                                                            </span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="pl-12 text-gray-500 text-sm">No votes yet.</p>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            @endforeach
        @else
            <p class="text-gray-600">No active elections at the moment.</p>
        @endif
    </div>

    {{-- Toggle voter visibility --}}
    <script>
        document.querySelectorAll('.toggle-voters').forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const votersDiv = document.getElementById(targetId);
                votersDiv.classList.toggle('hidden');
                button.textContent = votersDiv.classList.contains('hidden') ? 'Show Voters' : 'Hide Voters';
            });
        });
    </script>
@endsection
