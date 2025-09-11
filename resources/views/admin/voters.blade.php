@extends('layouts.admin')

@section('title', 'Manage Voters')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Registered Voters</h2>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

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
                    <td>{{ $voter->name }}</td>
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
                            <form action="{{ route('admin.voters.toggle', $voter->id) }}" method="POST">
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
@endsection
