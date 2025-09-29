@extends('layouts.admin')

@section('title', 'Manage Voters')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Registered Voters</h2>

        {{-- Filter --}}
        <div class="mb-6 flex items-center gap-2">
            <form method="GET" action="{{ route('admin.voters.index') }}">
                <select name="filter" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-md px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#09182D] focus:border-[#09182D]">
                    <option value="">All Status</option>
                    <option value="eligible" {{ request('filter') == 'eligible' ? 'selected' : '' }}>Eligible</option>
                    <option value="not_eligible" {{ request('filter') == 'not_eligible' ? 'selected' : '' }}>Not Eligible
                    </option>
                </select>
            </form>
        </div>

        @if($voters->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Voter ID</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Skipped Elections</th>
                            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Eligibility</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($voters as $voter)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $voter->voter_id }}</td>
                                <td class="px-4 py-2 font-medium text-gray-800">{{ $voter->name }}</td>
                                <td class="px-4 py-2 text-gray-600">{{ $voter->email }}</td>

                                {{-- Skipped elections --}}
                                <td class="px-4 py-2 text-center">
                                    <button type="button"
                                        class="skipped-btn px-3 py-1 rounded-lg bg-gray-100 text-blue-600 hover:bg-blue-200 font-medium text-sm"
                                        data-elections='@json($voter->skippedElections())'>
                                        {{ $voter->skippedElectionsCount() }}
                                    </button>
                                </td>

                                {{-- Eligibility --}}
                                <td class="px-4 py-2 text-center">
                                    @if ($voter->finalEligibility())
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                            Eligible
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                            Not Eligible
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-2 text-center">
                                    <form action="{{ route('admin.voters.toggle', $voter->id) }}" method="POST"
                                        class="inline toggle-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-3 py-1 text-sm rounded-lg
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

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $voters->appends(request()->query())->links() }}
            </div>
        @else
            <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 rounded">
                <p class="text-sm">No voters found matching the selected filter.</p>
            </div>
        @endif
    </div>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Skipped elections popup
        document.querySelectorAll('.skipped-btn').forEach(button => {
            button.addEventListener('click', function () {
                let elections = JSON.parse(this.dataset.elections);

                if (elections.length > 0) {
                    Swal.fire({
                        title: 'Skipped Elections',
                        html: `
                                <ul class="text-left space-y-2">
                                    ${elections.map(e => `<li class="p-2 bg-gray-50 rounded border">${e}</li>`).join('')}
                                </ul>
                            `,
                        icon: 'info',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#09182D',
                        width: 500
                    });
                } else {
                    Swal.fire({
                        title: 'No Skipped Elections',
                        text: "This voter has not skipped any elections.",
                        icon: 'success',
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#09182D',
                    });
                }
            });
        });

        // Toggle confirmation
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

        // Success/Error toast
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 1200,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    timer: 1200,
                    showConfirmButton: false,
                    position: 'top-end',
                    toast: true
                });
            @endif
            });
    </script>
@endsection
