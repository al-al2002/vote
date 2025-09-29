@extends('layouts.admin')

@section('title', 'Create Election')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Create New Election</h1>

    <form action="{{ route('admin.elections.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded-lg px-3 py-2">
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Description</label>
            <textarea name="description" rows="4"
                class="w-full border rounded-lg px-3 py-2">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Start Date & Time</label>
            <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                class="w-full border rounded-lg px-3 py-2">
            @error('start_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">End Date & Time</label>
            <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                class="w-full border rounded-lg px-3 py-2">
            @error('end_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            Create Election
        </button>
    </form>
@endsection
