@extends('layouts.user')

@section('title', 'Voter Dashboard')

@section('content')
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Welcome Section --}}
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-white-900 mb-2">
                Welcome back, {{ Auth::user()->name ?? 'Voter' }}!
            </h2>
            <p class="text-white-600">
                Ready to make your voice heard? Check out the elections below.
            </p>
        </div>

        {{-- Quick Stats --}}
        <div class="flex flex-wrap gap-6 mb-8">
            {{-- Active Elections --}}
            <div onclick="showSection('active')"
                class="cursor-pointer flex-1 min-w-[150px] p-6 rounded-xl shadow-sm border border-gray-200 bg-[#09182D] text-white hover:bg-[#0f2b4f] transition">
                <p class="text-gray-300 text-sm font-medium">Active Elections</p>
                <p class="text-3xl font-bold mt-1">{{ $activeElections }}</p>
            </div>

            {{-- Upcoming Elections --}}
            <div onclick="showSection('upcoming')"
                class="cursor-pointer flex-1 min-w-[150px] p-6 rounded-xl shadow-sm border border-gray-200 bg-[#09182D] text-white hover:bg-[#0f2b4f] transition">
                <p class="text-gray-300 text-sm font-medium">Upcoming Elections</p>
                <p class="text-3xl font-bold mt-1">{{ $upcomingElections }}</p>
            </div>

            {{-- Closed Elections --}}
            <div onclick="showSection('closed')"
                class="cursor-pointer flex-1 min-w-[150px] p-6 rounded-xl shadow-sm border border-gray-200 bg-[#09182D] text-white hover:bg-[#0f2b4f] transition">
                <p class="text-gray-300 text-sm font-medium">Closed Elections</p>
                <p class="text-3xl font-bold mt-1">{{ $closedElections }}</p>
            </div>

            {{-- Votes Cast --}}
            <div class="flex-1 min-w-[150px] p-6 rounded-xl shadow-sm border border-gray-200 bg-[#09182D] text-white">
                <p class="text-gray-300 text-sm font-medium">Votes Cast</p>
                <p class="text-3xl font-bold mt-1">{{ Auth::user()->votes()->count() }}</p>
            </div>

        {{-- Voting Status --}}
    {{-- Voting Status --}}
    <div class="flex-1 min-w-[150px] p-6 rounded-xl shadow-sm border border-gray-200 bg-[#09182D] text-white">
        <p class="text-gray-300 text-sm font-medium">Voting Status</p>
        <p class="text-lg font-bold mt-1
            {{ Auth::user()->is_eligible ? 'text-green-400' : 'text-red-400' }}">

            @if(!Auth::user()->is_eligible)
                Not Eligible
            @elseif(Auth::user()->has_voted)
                Already Voted
            @else
                Eligible
            @endif
        </p>
    </div>


        </div>

        {{-- Election Sections --}}
        <div class="mb-8">
        {{-- Active Elections --}}
        <div id="active-section">
            <h3 class="text-2xl font-bold text-white mb-6">Active Elections</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($activeElectionsList as $election)
                    <div class="p-6 rounded-xl shadow-sm border border-gray-700 bg-[#10243F] hover:shadow-lg transition">
                        <h4 class="text-xl font-bold text-yellow-400">{{ $election->title }}</h4>
                        <p class="text-gray-300 mt-2">{{ $election->description }}</p>
                        <p class="text-sm text-gray-400 mt-2">
                            Ends: {{ \Carbon\Carbon::parse($election->end_date)->format('M d, Y') }}
                        </p>

                        @if(Auth::user()->is_eligible)
                            <a href="{{ route('user.elections.show', $election->id) }}"
                                class="mt-4 inline-block px-4 py-2 bg-yellow-500 text-[#09182D] font-semibold rounded-lg hover:bg-yellow-400 transition">
                                Vote Now
                            </a>
                        @else
                            <button type="button"
                                class="mt-4 inline-block px-4 py-2 bg-gray-500 text-gray-300 font-semibold rounded-lg cursor-not-allowed"
                                disabled>
                                Not Eligible
                            </button>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-400 col-span-full text-center">No active elections right now.</p>
                @endforelse
            </div>
        </div>

            {{-- Upcoming Elections --}}
            <div id="upcoming-section" class="hidden">
                <h3 class="text-2xl font-bold text-white mb-6">Upcoming Elections</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($upcomingElectionsList as $election)
                        <div class="p-6 rounded-xl shadow-sm border border-gray-700 bg-[#10243F] hover:shadow-lg transition">
                            <h4 class="text-xl font-bold text-blue-400">{{ $election->title }}</h4>
                            <p class="text-gray-300 mt-2">{{ $election->description }}</p>
                            <p class="text-sm text-gray-400 mt-2">
                                Starts: {{ \Carbon\Carbon::parse($election->start_date)->format('M d, Y') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-400 col-span-full text-center">No upcoming elections.</p>
                    @endforelse
                </div>
            </div>

            {{-- Closed Elections --}}
            <div id="closed-section" class="hidden">
                <h3 class="text-2xl font-bold text-white mb-6">Closed Elections</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($closedElectionsList as $election)
                        <div class="p-6 rounded-xl shadow-sm border border-gray-700 bg-[#10243F] hover:shadow-lg transition">
                            <h4 class="text-xl font-bold text-red-400">{{ $election->title }}</h4>
                            <p class="text-gray-300 mt-2">{{ $election->description }}</p>
                            <p class="text-sm text-gray-400 mt-2">
                                Ended: {{ \Carbon\Carbon::parse($election->end_date)->format('M d, Y') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-400 col-span-full text-center">No closed elections.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Voting History --}}
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-white-900 mb-6">Your Voting History</h3>
            <ul class="list-disc pl-6 text-gray-700">
                @forelse(Auth::user()->votes as $vote)
                    <li>
                        Voted in <span class="font-semibold">{{ $vote->election->name }}</span>
                        on {{ $vote->created_at->format('M d, Y h:i A') }}
                    </li>
                @empty
                    <li class="text-gray-500">You haven’t voted yet.</li>
                @endforelse
            </ul>
        </div>

        {{-- Toggle Sections Script --}}
        <script>
            function showSection(section) {
                document.getElementById('active-section').classList.add('hidden');
                document.getElementById('upcoming-section').classList.add('hidden');
                document.getElementById('closed-section').classList.add('hidden');

                document.getElementById(section + '-section').classList.remove('hidden');
            }
        </script>
@endsection
