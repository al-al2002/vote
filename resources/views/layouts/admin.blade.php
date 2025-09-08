<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VoteMaster')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-gradient {
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 50%, #3b82f6 100%);
        }

        .menu-item {
            transition: all 0.3s;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(8px);
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            border-right: 4px solid #fbbf24;
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .content-area {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
        }

        .sidebar-scroll {
            overflow-y: auto;
            flex-grow: 1;
        }
    </style>
</head>

<body class="bg-gray-100 flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <div class="sidebar-gradient w-64 shadow-2xl flex flex-col">
        {{-- Logo --}}
        <div class="p-6 border-b border-white border-opacity-20">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg">VoteMaster</h1>
                    <p class="text-blue-200 text-xs">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- Admin Profile --}}
        <div class="p-6 border-b border-white border-opacity-20">
            <div class="flex items-center space-x-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                    <span
                        class="text-white font-semibold text-lg">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                </div>
                <div>
                    <h3 class="text-white font-semibold">{{ auth()->user()->name }}</h3>
                    <p class="text-blue-200 text-sm">System Administrator</p>
                    <div class="flex items-center mt-1">
                        <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                        <span class="text-green-300 text-xs">Online</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Menu --}}
        <nav class="flex-1 py-6 sidebar-scroll">
            <div class="px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="menu-item flex items-center px-4 py-3 text-white rounded-lg @if(request()->routeIs('admin.dashboard')) active @endif">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg> Dashboard
                </a>

                <a href="#" class="menu-item flex items-center px-4 py-3 text-white rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2z"></path>
                    </svg> Elections
                </a>

                <a href="#" class="menu-item flex items-center px-4 py-3 text-white rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg> Candidates
                </a>
            </div>
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-white border-opacity-20">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item flex items-center px-4 py-3 text-white rounded-lg w-full">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg> Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <div>
                <h2 id="pageTitle" class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                <p class="text-gray-600 text-sm">Welcome back, manage your voting system</p>
            </div>
            @yield('header-actions')
        </header>

        <main class="flex-1 overflow-y-auto content-area p-6">
            @yield('content')
        </main>
    </div>

</body>

</html>
