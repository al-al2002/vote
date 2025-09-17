<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VoteMaster - Voter Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #09182D;
        }

        .card {
            background: #10243F;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            transition: transform .2s, box-shadow .2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .4);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: .75rem;
            font-weight: 600;
        }

        .status-active {
            background: #10b981;
            color: white;
        }

        .status-upcoming {
            background: #3b82f6;
            color: white;
        }

        .status-closed {
            background: #ef4444;
            color: white;
        }

        .vote-btn {
            background: #facc15;
            color: #09182D;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            transition: transform .2s;
        }

        .vote-btn:hover {
            transform: scale(1.05)
        }
    </style>
</head>

<body class="text-white">

    {{-- Header --}}
    <header class="bg-[#09182D] shadow-md border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-yellow-400 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold">VoteMaster</h1>
                    <p class="text-gray-400 text-sm">Your Voice, Your Vote</p>
                </div>
            </div>

            <div class="flex items-center space-x-6">

                <a href="{{ route('user.results.index') }}"
                    class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold transition">
                    Results
                </a>

                <div class="text-right">
                    <p class="font-semibold">{{ Auth::user()->name ?? 'Guest' }}</p>
                    <p class="text-gray-400 text-sm">Voter ID: {{ Auth::user()->voter_id ?? 'VS2024001' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        @yield('content')
    </main>

    @yield('modals')
    @yield('scripts')
</body>

</html>
