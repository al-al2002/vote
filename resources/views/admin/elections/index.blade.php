@extends('layouts.admin')

@section('title', 'Manage Elections')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Elections</h1>

    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('admin.elections.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">+ New
            Election</a>

        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.elections.index') }}">
            <select name="status" onchange="this.form.submit()" class="border rounded-lg px-3 py-2">
                <option value="">All</option>
                <option value="active" {{ $filter === 'active' ? 'selected' : '' }}>Active</option>
                <option value="upcoming" {{ $filter === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="closed" {{ $filter === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </form>
    </div>

    <table class="min-w-full mt-6 border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">Title</th>
                <th class="px-4 py-2 border">Start Date</th>
                <th class="px-4 py-2 border">End Date</th>
                <th class="px-4 py-2 border">Status</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($elections as $election)
                <tr>
                    <td class="px-4 py-2 border">{{ $election->title }}</td>
                    <td class="px-4 py-2 border">{{ $election->start_date }}</td>
                    <td class="px-4 py-2 border">{{ $election->end_date }}</td>
                    <td class="px-4 py-2 border">
                        @php
                            $today = now();
                            if ($election->start_date > $today) {
                                $status = 'Upcoming';
                            } elseif ($election->end_date < $today) {
                                $status = 'Closed';
                            } else {
                                $status = 'Active';
                            }
                        @endphp
                        <span class="px-2 py-1 rounded
                                    {{ $status === 'Active' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $status === 'Upcoming' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $status === 'Closed' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ $status }}
                        </span>
                    </td>
                    <td class="px-4 py-2 border flex space-x-2">
                        <a href="{{ route('admin.elections.edit', $election->id) }}"
                            class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</a>

                        <form action="{{ route('admin.elections.destroy', $election->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this election?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">No elections found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
