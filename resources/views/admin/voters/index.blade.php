@extends('layouts.admin')

@section('title', 'Manage Voters')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Registered Voters</h2>

        {{-- Filter --}}
        <div class="mb-4 flex items-center gap-2">
            <form method="GET" action="{{ route('admin.voters.index') }}">
                <label for="filter" class="text-gray-700 font-medium">Filter:</label>
                <select name="eligible" id="filter" class="border border-gray-300 rounded-lg px-3 py-2"
                    onchange="this.form.submit()">
                    <option value="">All Voters</option>
                    <option value="1" {{ request('eligible') === '1' ? 'selected' : '' }}>Eligible</option>
                    <option value="0" {{ request('eligible') === '0' ? 'selected' : '' }}>Not Eligible</option>
                </select>
            </form>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 border-b">Profile</th>
                    <th class="p-3 border-b">Voter ID</th>
                    <th class="p-3 border-b">Name</th>
                    <th class="p-3 border-b">Email</th>
                    <th class="p-3 border-b">Skipped Elections</th>
                    <th class="p-3 border-b">Eligible</th>
                    <th class="p-3 border-b text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($voters as $voter)
                    <tr class="hover:bg-gray-50">
                        {{-- Profile --}}
                        <td class="p-3 border-b">
                            @if ($voter->profile_photo)
                                <img src="{{ asset('storage/' . $voter->profile_photo) }}" alt="Profile"
                                    class="w-10 h-10 rounded-full object-cover border border-gray-300">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">N/A
                                </div>
                            @endif
                        </td>
                        <td class="p-3 border-b">{{ $voter->voter_id }}</td>

                        <td class="p-3 border-b">{{ $voter->name }}</td>
                        <td class="p-3 border-b">{{ $voter->email }}</td>
                        <td class="p-3 border-b text-center">{{ $voter->skippedElectionsCount() }}</td>


                        <td class="p-3 border-b">
                            @if ($voter->is_eligible)
                                <span
                                    class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Eligible</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Not
                                    Eligible</span>

                                {{-- Show auto-flag note only if NOT eligible --}}
                                @if ($voter->isAutoFlagged())
                                    <div class="text-xs text-gray-500 mt-1">(Auto flagged due to skipped elections)</div>
                                @endif
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="p-3 border-b text-center">
                            <form class="toggle-eligibility-form inline-block"
                                action="{{ route('admin.voters.toggle', $voter->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-3 py-1 text-sm rounded-lg text-white
                                    {{ $voter->is_eligible ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                    {{ $voter->is_eligible ? 'Mark Not Eligible' : 'Mark Eligible' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 p-4">No voters found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">{{ $voters->withQueryString()->links() }}</div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.toggle-eligibility-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const button = form.querySelector('button');
                const actionText = button.textContent.trim();

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to "${actionText}" this voter.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
