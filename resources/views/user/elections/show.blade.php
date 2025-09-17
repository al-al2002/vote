@extends('layouts.user')

@section('title', $election->title ?? 'Election')

@section('content')
    @php
        $userVoteId = $userVote->candidate_id ?? null;
    @endphp

    <div class="max-w-5xl mx-auto bg-[#10243F] text-white shadow rounded-xl p-8 relative">

        {{-- Back Button --}}
        <div class="absolute top-4 left-4">
            <a href="{{ route('user.dashboard') }}"
                class="inline-block bg-gray-700 hover:bg-gray-600 text-white px-5 py-2 rounded-lg font-semibold transition">
                ← Back to Dashboard?
            </a>
        </div>

        {{-- Election Info --}}
        <div class="text-center mb-10">
            <h2 class="text-4xl font-extrabold text-yellow-400 mb-3">{{ $election->title }}</h2>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto">{{ $election->description }}</p>
            <p class="text-sm text-gray-400 mt-2">
                {{ \Carbon\Carbon::parse($election->start_date)->format('M d, Y') }} -
                {{ \Carbon\Carbon::parse($election->end_date)->format('M d, Y') }}
            </p>
        </div>

        {{-- Candidates Section --}}
        <h3 class="text-2xl font-semibold mb-6 text-center">List of Candidates</h3>

        @if($userVoteId)
            <p class="text-green-400 font-semibold mb-8 text-center">
                ✅ You already voted in this election
            </p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($candidates as $candidate)
                    <div
                        class="relative bg-[#09182D] border rounded-2xl p-6 text-center hover:scale-105 transition border-gray-700">
                        {{-- Candidate Photo --}}
                        <div class="relative w-28 h-28 mx-auto mb-4">
                            <img src="{{ $candidate->photo_url ?? (isset($candidate->photo) ? asset('storage/' . $candidate->photo) : 'https://via.placeholder.com/150') }}"
                                alt="{{ $candidate->name }}"
                                class="w-28 h-28 rounded-full mx-auto object-cover border-4 border-gray-600">
                        </div>

                        {{-- Candidate Info --}}
                        <h4 class="text-lg font-bold">{{ $candidate->name }}</h4>
                        <p class="text-gray-400 text-sm">{{ $candidate->position ?? 'Candidate' }}</p>

                        {{-- Vote Button --}}
                        <button
                            onclick="confirmVote('{{ $candidate->id }}', '{{ $candidate->name }}', '{{ $candidate->photo_url ?? asset('storage/' . $candidate->photo) }}')"
                            class="mt-4 bg-yellow-400 text-[#09182D] px-5 py-2 rounded-lg font-semibold hover:bg-yellow-300 transition">
                            Vote
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmVote(candidateId, candidateName, candidatePhoto) {
            Swal.fire({
                title: 'Are you sure?',
                html: `<img src="${candidatePhoto}" class="w-24 h-24 rounded-full mx-auto mb-2"><p>Vote for <strong>${candidateName}</strong>?</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, vote!',
                cancelButtonText: 'No, cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("user.elections.vote", $election->id) }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="candidate_id" value="${candidateId}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Voted Successfully!',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                willClose: () => {
                    window.location.href = '{{ route("user.elections.index") }}';
                }
            });
        @elseif(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: '{{ session("error") }}',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        @endif
    </script>
@endsection
