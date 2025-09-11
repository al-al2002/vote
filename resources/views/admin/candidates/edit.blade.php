@extends('layouts.admin')

@section('title', 'Edit Candidate')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Candidate</h1>

    <form action="{{ route('admin.candidates.update', $candidate) }}" method="POST" enctype="multipart/form-data"
        class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Name</label>
            <input type="text" name="name" value="{{ $candidate->name }}" class="w-full border rounded-lg px-3 py-2"
                required>
        </div>

        <div>
            <label class="block font-medium">Position</label>
            <input type="text" name="position" value="{{ $candidate->position }}" class="w-full border rounded-lg px-3 py-2"
                required>
        </div>

        <div>
            <label class="block font-medium">Election</label>
            <select name="election_id" class="w-full border rounded-lg px-3 py-2" required>
                @foreach($elections as $election)
                    <option value="{{ $election->id }}" {{ $candidate->election_id == $election->id ? 'selected' : '' }}>
                        {{ $election->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Photo</label>
            @if($candidate->photo)
                <img src="{{ asset('storage/' . $candidate->photo) }}" class="w-20 h-20 rounded-full object-cover mb-2">
            @endif
            <input type="file" name="photo" accept="image/*" class="w-full border rounded-lg px-3 py-2">
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">
            Update Candidate
        </button>
    </form>
@endsection
