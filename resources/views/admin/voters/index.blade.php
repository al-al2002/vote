@extends('layouts.admin')

@section('title', 'Manage Voters')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Registered Voters</h2>

    {{-- 🔎 Filter Dropdown --}}
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.voters.index') }}">
            <select name="filter" onchange="this.form.submit()" class="border-gray-300 rounded-md">
                <option value="">-- Filter Voters --</option>
                <option value="eligible" {{ request('filter') == 'eligible' ? 'selected' : '' }}>Eligible</option>
                <option value="not_eligible" {{ request('filter') == 'not_eligible' ? 'selected' : '' }}>Not Eligible</option>
            </select>
        </form>
    </div>

    <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Voter ID</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Name</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Email</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Skipped Elections</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Eligibility</th>
                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($voters as $voter)
                <tr>
                    <td class="px-4 py-2">{{ $voter->voter_id }}</td>
                    <td class="px-4 py-2">{{ $voter->name }}</td>
                    <td class="px-4 py-2">{{ $voter->email }}</td>
                    <td class="px-4 py-2">
                        {{ $voter->skippedElectionsCount() }}
                    </td>
                    <td class="px-4 py-2">
                        @if ($voter->finalEligibility())
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Eligible</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Not Eligible</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-center">
                        <form action="{{ route('admin.voters.toggle', $voter->id) }}" method="POST" class="inline toggle-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1 text-sm rounded-lg
                                {{ $voter->finalEligibility() ? 'bg-red-500 text-white hover:bg-red-600' : 'bg-green-500 text-white hover:bg-green-600' }}">
                                {{ $voter->finalEligibility() ? 'Mark Not Eligible' : 'Mark Eligible' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ✅ SweetAlert Script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.toggle-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This will update the voter's eligibility status.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
