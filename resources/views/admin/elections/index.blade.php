@extends('layouts.admin')

@section('title', 'Manage Elections')

@section('content')
    <h1 class="text-2xl font-bold mb-4 text-[#09182D]">Elections</h1>

    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('admin.elections.create') }}"
            class="bg-[#09182D] text-white px-4 py-2 rounded-lg hover:bg-[#0f2345] transition">+ New Election</a>

        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.elections.index') }}">
            <select name="status" onchange="this.form.submit()"
                class="border border-[#09182D] rounded-lg px-3 py-2 text-[#09182D]">
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
                            class="bg-[#09182D] text-white px-3 py-1 rounded hover:bg-[#0f2345] transition">Edit</a>

                        <form class="delete-election-form" action="{{ route('admin.elections.destroy', $election->id) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">No elections found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Delete confirmation for elections
        document.querySelectorAll('.delete-election-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This election will be permanently deleted!",
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

        // Success / Error popups
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        timer: 1000,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                }, 200);
            @endif

            @if(session('error'))
                setTimeout(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: "{{ session('error') }}",
                        timer: 1000,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true
                    });
                }, 200);
            @endif
        });
    </script>
@endsection
