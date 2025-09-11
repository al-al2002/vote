@extends('layouts.admin')

@section('title', 'Add Candidate')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Add Candidate</h1>

    <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded-lg px-3 py-2" required>
        </div>

        <div>
            <label class="block font-medium">Position</label>
            <input type="text" name="position" class="w-full border rounded-lg px-3 py-2" required>
        </div>

        <div>
            <label class="block font-medium">Election</label>
            <select name="election_id" class="w-full border rounded-lg px-3 py-2" required>
                <option value="">-- Select Election --</option>
                @foreach($elections as $election)
                    <option value="{{ $election->id }}">{{ $election->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium">Photo</label>
            <input type="file" name="photo" accept="image/*" class="w-full border rounded-lg px-3 py-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Save Candidate
        </button>
    </form>
@endsection
