@extends('layouts.admin')

@section('title', 'Edit Election')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Election</h1>

    <form action="{{ route('admin.elections.update', $election->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Title</label>
            <input type="text" name="title" value="{{ old('title', $election->title) }}"
                class="w-full border rounded-lg px-3 py-2">
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium">Description</label>
            <textarea name="description" rows="4"
                class="w-full border rounded-lg px-3 py-2">{{ old('description', $election->description) }}</textarea>
            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

    <div>
        <label class="block font-medium">Start Date</label>
        <input type="date" name="start_date" value="{{ old('start_date', $election->start_date->format('Y-m-d')) }}"
            class="w-full border rounded-lg px-3 py-2">
        @error('start_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-medium">End Date</label>
        <input type="date" name="end_date" value="{{ old('end_date', $election->end_date->format('Y-m-d')) }}"
            class="w-full border rounded-lg px-3 py-2">
        @error('end_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>


        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Update Election
        </button>
    </form>
@endsection
