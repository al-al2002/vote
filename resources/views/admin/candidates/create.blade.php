@extends('layouts.admin')

@section('title', 'Add Candidates')

@section('content')
    <h1 class="text-2xl font-bold mb-4 text-[#09182D]">Add Candidates</h1>

    <form action="{{ route('admin.candidates.storeMultiple') }}" method="POST" enctype="multipart/form-data"
        class="space-y-4">
        @csrf

        {{-- Election Dropdown --}}
        <div>
            <label class="block font-medium">Election</label>
            <select name="election_id" class="w-full border rounded-lg px-3 py-2" required>
                <option value="">-- Select Election --</option>
                @foreach($elections as $election)
                    @php
                        $now = \Carbon\Carbon::now();
                        if ($now->gt($election->end_date))
                            continue;
                        $status = $now->between($election->start_date, $election->end_date) ? 'Active' : 'Upcoming';
                    @endphp
                    <option value="{{ $election->id }}">{{ $election->title }} ({{ $status }})</option>
                @endforeach
            </select>
        </div>

        {{-- Candidate Fields Container --}}
        <div id="candidates-container">
            <div class="candidate-item space-y-2 border p-4 rounded-lg mb-4">
                <div>
                    <label class="block font-medium">Name</label>
                    <input type="text" name="candidates[0][name]" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block font-medium">Position</label>
                    <input type="text" name="candidates[0][position]" class="w-full border rounded-lg px-3 py-2" required>
                </div>
                <div>
                    <label class="block font-medium">Photo</label>
                    <input type="file" name="candidates[0][photo]" accept="image/*"
                        class="w-full border rounded-lg px-3 py-2">
                </div>
                <button type="button"
                    class="remove-candidate bg-red-500 text-white px-3 py-1 rounded mt-2 hover:bg-red-600 transition">Remove</button>
            </div>
        </div>

        {{-- Add Another Candidate --}}
        <button type="button" id="add-candidate"
            class="bg-[#09182D] text-white px-4 py-2 rounded-lg hover:bg-[#0f2345] transition">
            + Add Another Candidate
        </button>

        {{-- Submit --}}
        <button type="submit" class="bg-[#09182D] text-white px-4 py-2 rounded-lg hover:bg-[#0f2345] transition mt-4">
            Save Candidates
        </button>
    </form>

    {{-- JS for dynamic candidate fields --}}
    <script>
        let candidateIndex = 1;

        document.getElementById('add-candidate').addEventListener('click', function () {
            const container = document.getElementById('candidates-container');
            const newItem = document.querySelector('.candidate-item').cloneNode(true);

            newItem.querySelectorAll('input').forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace(/\d+/, candidateIndex));
                input.value = '';
            });

            container.appendChild(newItem);
            candidateIndex++;
        });

        document.getElementById('candidates-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-candidate')) {
                e.target.closest('.candidate-item').remove();
            }
        });
    </script>

    {{-- SweetAlert2 for success/error --}}
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session()->has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                timer: 1500,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
        @endif

        @if(session()->has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                timer: 1500,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
        @endif
    });
</script>

@endsection
