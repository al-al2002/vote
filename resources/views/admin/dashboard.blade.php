@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header-actions')
    <div class="flex items-center gap-4">
        <p class="text-gray-700 font-medium text-lg">
            👋 Hi, {{ Auth::user()->name }}
        </p>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Active Elections --}}
        <div class="stat-card p-6 rounded-2xl shadow-xl border border-gray-100 bg-gradient-to-r from-blue-500 to-indigo-500 text-white transform hover:scale-105 transition duration-300">
            <p class="text-sm font-medium opacity-80">Active Elections</p>
            <p class="text-3xl font-bold mt-1">{{ $activeElections }}</p>
        </div>

        {{-- Upcoming Elections --}}
        <div class="stat-card p-6 rounded-2xl shadow-xl border border-gray-100 bg-gradient-to-r from-green-400 to-teal-500 text-white transform hover:scale-105 transition duration-300">
            <p class="text-sm font-medium opacity-80">Upcoming Elections</p>
            <p class="text-3xl font-bold mt-1">{{ $upcomingElections }}</p>
        </div>

        {{-- Closed Elections --}}
        <div class="stat-card p-6 rounded-2xl shadow-xl border border-gray-100 bg-gradient-to-r from-red-400 to-pink-500 text-white transform hover:scale-105 transition duration-300">
            <p class="text-sm font-medium opacity-80">Closed Elections</p>
            <p class="text-3xl font-bold mt-1">{{ $closedElections }}</p>
        </div>

        {{-- Total Voters --}}
        <div class="stat-card p-6 rounded-2xl shadow-xl border border-gray-100 bg-gradient-to-r from-yellow-400 to-orange-500 text-white transform hover:scale-105 transition duration-300">
            <p class="text-sm font-medium opacity-80">Total Voters</p>
            <p class="text-3xl font-bold mt-1">{{ $totalVoters }}</p>
        </div>
    </div>

    {{-- Chart Section --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Votes Per Election</h3>
        <canvas id="votesChart" height="150"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('votesChart').getContext('2d');

        // Create gradient for line fill
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(9, 24, 45, 0.4)');
        gradient.addColorStop(1, 'rgba(9, 24, 45, 0)');

        const votesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Votes',
                    data: @json($chartData),
                    borderColor: '#09182D',
                    backgroundColor: gradient,
                    tension: 0.4, // Smooth curve
                    fill: true,
                    pointBackgroundColor: '#09182D',
                    pointBorderColor: '#09182D',
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#09182D',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e5e7eb', drawBorder: false },
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { color: '#e5e7eb', drawBorder: false },
                        ticks: { color: '#374151', font: { weight: 500 } }
                    }
                }
            }
        });
    </script>
@endsection
