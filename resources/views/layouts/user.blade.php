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
    </style>
</head>

<body class="text-white">

    {{-- Header --}}
    <header class="bg-[#09182D] shadow-md border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center justify-between">

            {{-- Logo --}}
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

            {{-- Right Section --}}
            <div class="flex items-center space-x-4">
                <a href="{{ route('user.results.index') }}"
                    class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold transition">
                    Results
                </a>

                <a href="{{ route('user.live-monitor.index') }}"
                    class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-lg font-semibold transition">
                    Live Monitor
                </a>

                <a href="{{ route('user.messages.index') }}"
                    class="relative px-4 py-2 rounded-lg font-semibold transition
                          {{ request()->routeIs('user.messages.*') ? 'bg-yellow-500 text-black' : 'bg-yellow-400 text-black hover:bg-yellow-500' }}">
                    📥 Inbox
                </a>

                {{-- User Dropdown --}}
                <div class="relative">
                    <button id="userMenuBtn" class="flex items-center space-x-2 focus:outline-none">
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-yellow-400">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-600 text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="hidden sm:block text-left">
                            <span class="font-semibold block">{{ Auth::user()->name ?? 'Guest' }}</span>
                            <span class="text-xs text-gray-400">ID: {{ Auth::user()->voter_id ?? 'N/A' }}</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown menu --}}
                <div id="userMenu"
                    class="absolute right-0 mt-2 w-48 bg-[#10243F] border border-gray-700 rounded-lg shadow-lg hidden z-50">

                    <a href="{{ route('user.profile.edit') }}" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">
                        Edit Profile
                    </a>

                    <a href="{{ route('user.profile.settings') }}" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">
                        Settings
                    </a>

                    <a href="{{ route('user.password.change') }}" class="block px-4 py-2 text-sm text-white hover:bg-gray-700">
                        Change Password
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-400 hover:bg-gray-700">
                            Logout
                        </button>
                    </form>
                </div>

                </div>
            </div>

        </div>
    </header>

    {{-- Toggle Dropdown --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const btn = document.getElementById("userMenuBtn");
            const menu = document.getElementById("userMenu");

            btn.addEventListener("click", (e) => {
                e.stopPropagation();
                menu.classList.toggle("hidden");
            });

            document.addEventListener("click", (e) => {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add("hidden");
                }
            });
        });
    </script>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        @yield('content')
    </main>

    @yield('modals')
    @yield('scripts')
</body>

</html>
