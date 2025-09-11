@extends('layouts.admin')

@section('title', 'Manage Candidates')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Candidates</h1>
    <a href="{{ route('admin.candidates.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">+ Add Candidate</a>

    <table class="min-w-full mt-6 border border-gray-200">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">Photo</th>
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Position</th>
                <th class="px-4 py-2 border">Election</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($candidates as $candidate)
                <tr>
                    <td class="px-4 py-2 border">
                        @if($candidate->photo)
                            <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
                                class="w-12 h-12 rounded-full object-cover">
                        @else
                            <span class="text-gray-400">No photo</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 border">{{ $candidate->name }}</td>
                    <td class="px-4 py-2 border">{{ $candidate->position }}</td>
                    <td class="px-4 py-2 border">{{ $candidate->election->title }}</td>
                    <td class="px-4 py-2 border flex gap-2">
                        <a href="{{ route('admin.candidates.edit', $candidate) }}"
                            class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</a>
                        <form action="{{ route('admin.candidates.destroy', $candidate) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded"
                                onclick="return confirm('Delete this candidate?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
