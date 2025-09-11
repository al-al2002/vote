<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ElectionController extends Controller
{
    /**
     * Display a listing of the elections with optional status filtering.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $filter = $request->get('status');

        $query = Election::query();

        // Apply filter
        if ($filter === 'active') {
            $query->where('start_date', '<=', $today)->where('end_date', '>=', $today);
        } elseif ($filter === 'upcoming') {
            $query->where('start_date', '>', $today);
        } elseif ($filter === 'closed') {
            $query->where('end_date', '<', $today);
        }

        $elections = $query->orderBy('start_date', 'desc')->get();

        return view('admin.elections.index', compact('elections', 'filter'));
    }

    /**
     * Show the form for creating a new election.
     */
    public function create()
    {
        return view('admin.elections.create');
    }

    /**
     * Store a newly created election in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Election::create([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()
            ->route('admin.elections.index')
            ->with('success', 'Election created successfully.');
    }

    /**
     * Show the form for editing the specified election.
     */
    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    /**
     * Update the specified election in storage.
     */
    public function update(Request $request, Election $election)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $election->update([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()
            ->route('admin.elections.index')
            ->with('success', 'Election updated successfully.');
    }

    /**
     * Remove the specified election from storage.
     */
    public function destroy(Election $election)
    {
        $election->delete();

        return redirect()
            ->route('admin.elections.index')
            ->with('success', 'Election deleted successfully.');
    }
}
