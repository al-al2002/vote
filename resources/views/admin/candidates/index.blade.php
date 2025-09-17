@extends('layouts.admin')

@section('title', 'Manage Candidates')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Candidates</h1>

    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('admin.candidates.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            + Add Candidate
        </a>

        {{-- Filter by Election --}}
        <form method="GET" action="{{ route('admin.candidates.index') }}">
            <select name="election_id" onchange="this.form.submit()" class="border rounded-lg px-3 py-2">
                <option value="">All Elections</option>
                @foreach($elections as $election)
                    <option value="{{ $election->id }}" {{ request('election_id') == $election->id ? 'selected' : '' }}>
                        {{ $election->title }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

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
            @forelse ($candidates as $candidate)
                        <tr>
                            {{-- Photo --}}
                            <td class="px-4 py-2 border">
                                @if($candidate->photo)
                                    <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}"
                                        class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <span class="text-gray-400">No photo</span>
                                @endif
                            </td>

                            {{-- Name --}}
                            <td class="px-4 py-2 border">{{ $candidate->name }}</td>

                            {{-- Position --}}
                            <td class="px-4 py-2 border">{{ $candidate->position }}</td>

                            {{-- Election & Status --}}
                            <td class="px-4 py-2 border flex items-center gap-2">
                                <span>{{ $candidate->election->title ?? 'N/A' }}</span>
                                @if($candidate->election)
                                    @php
                $now = \Carbon\Carbon::now();
                if ($candidate->election->start_date > $now) {
                    $statusClass = 'bg-green-200 text-green-800'; // Upcoming
                    $statusText = 'Upcoming';
                } elseif ($candidate->election->end_date < $now) {
                    $statusClass = 'bg-red-200 text-red-800'; // Closed
                    $statusText = 'Closed';
                } else {
                    $statusClass = 'bg-blue-200 text-blue-800'; // Active
                    $statusText = 'Active';
                }
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                @endif
                            </td>

                        {{-- Actions --}}
                        <td class="px-4 py-2 border">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.candidates.edit', $candidate) }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm text-center">
                                    Edit
                                </a>

                                <form class="delete-form" action="{{ route('admin.candidates.destroy', $candidate) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm text-center">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>

                        </tr>

            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">No candidates found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Success popup after deletion
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('delete_success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: '{{ session('delete_success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endsection
