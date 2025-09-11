@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header-actions')
    <div class="flex items-center gap-4">
        <p class="text-gray-700 font-medium">
            HI, {{ Auth::user()->name }}
        </p>

    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Active Elections --}}
        <div class="stat-card p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-gray-600 text-sm font-medium">Active Elections</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $activeElections }}</p>
        </div>

        {{-- Upcoming Elections --}}
        <div class="stat-card p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-gray-600 text-sm font-medium">Upcoming Elections</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $upcomingElections }}</p>
        </div>

        {{-- Closed Elections --}}
        <div class="stat-card p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-gray-600 text-sm font-medium">Closed Elections</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $closedElections }}</p>
        </div>

        {{-- Total Voters --}}
        <div class="stat-card p-6 rounded-xl shadow-sm border border-gray-200">
            <p class="text-gray-600 text-sm font-medium">Total Voters</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalVoters }}</p>
        </div>

    </div>

        {{-- Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Votes Per Election</h3>
            <canvas id="votesChart" height="100"></canvas>
        </div>

        <script>
            const ctx = document.getElementById('votesChart').getContext('2d');
            const votesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Election 1', 'Election 2', 'Election 3'],
                    datasets: [{
                        label: 'Votes',
                        data: [120, 245, 180],
                        backgroundColor: ['#3b82f6', '#fbbf24', '#10b981']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>
@endsection
