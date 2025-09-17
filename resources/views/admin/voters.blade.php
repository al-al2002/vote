@extends('layouts.admin')

@section('title', 'Manage Voters')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Registered Voters</h2>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 border-b">Voter ID</th>
                    <th class="p-3 border-b">Name</th>
                    <th class="p-3 border-b">Email</th>
                    <th class="p-3 border-b">Eligible</th>
                    <th class="p-3 border-b text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($voters as $voter)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 border-b">{{ $voter->voter_id }}</td>
                        <td class="p-3 border-b">{{ $voter->name }}</td>
                        <td class="p-3 border-b">{{ $voter->email }}</td>
                        <td class="p-3 border-b">
                            @if($voter->is_eligible)
                                <span
                                    class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Eligible</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Not
                                    Eligible</span>
                            @endif
                        </td>
                        <td class="p-3 border-b text-center">
                            <form class="toggle-eligibility-form" action="{{ route('admin.voters.toggle', $voter->id) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-4 py-2 text-white rounded-lg
                                            {{ $voter->is_eligible ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                    {{ $voter->is_eligible ? 'Mark Not Eligible' : 'Mark Eligible' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $voters->links() }}
        </div>
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

        // Show session success message via SweetAlert2
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                timer: 2500,
                showConfirmButton: false
            });
        @endif
    </script>
@endsection
