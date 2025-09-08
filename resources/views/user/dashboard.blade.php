@extends('layouts.user')

@section('title', 'Voter Dashboard')

@section('content')
    {{-- Welcome Section --}}
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">
    Welcome back, {{ Auth::user()->name ?? 'Voter' }}!
</h2>
<p class="text-gray-600">
    @if(Auth::user()->role === 'admin')
        You are logged in as an administrator.
    @else
        Ready to make your voice heard? Check out the active elections below.
    @endif
</p>

    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="election-card card-hover p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Elections Available</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">3</p>
                </div>
            </div>
        </div>
        <div class="election-card card-hover p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Votes Cast</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">1</p>
                </div>
            </div>
        </div>
        <div class="election-card card-hover p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Voting Status</p>
                    <p class="text-lg font-bold text-green-600 mt-1">Eligible</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Elections --}}
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Active Elections</h3>

    </div>

    {{-- Voting History --}}

@endsection
