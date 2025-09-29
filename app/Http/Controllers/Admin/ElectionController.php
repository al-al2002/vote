<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ElectionController extends Controller
{
    /**
     * Display a listing of elections with optional status filtering & search.
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        $filter = $request->get('status');
        $keyword = $request->get('search');

        $query = Election::query();

        // 🔍 Search by title
        if (!empty($keyword)) {
            $query->where('title', 'like', "%{$keyword}%");
        }

        // 📌 Filter by status (time-aware)
        if ($filter === 'active') {
            $query->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now);
        } elseif ($filter === 'upcoming') {
            $query->where('start_date', '>', $now);
        } elseif ($filter === 'closed') {
            $query->where('end_date', '<', $now);
        }

        $elections = $query->orderBy('start_date', 'DESC')->get();

        return view('admin.elections.index', compact('elections', 'filter', 'keyword'));
    }

    /**
     * Show the form for creating a new election.
     */
    public function create()
    {
        return view('admin.elections.create');
    }

    /**
     * Store a newly created election.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Election::create($request->only([
            'title',
            'description',
            'start_date',
            'end_date',
        ]));

        return redirect()
            ->route('admin.elections.index')
            ->with('success', 'Election created successfully.');
    }

    /**
     * Display the specified election details with candidates.
     */
    public function show(Election $election)
    {
        $candidates = $election->candidates()
            ->withCount('votes')
            ->get();

        return view('admin.elections.show', compact('election', 'candidates'));
    }

    /**
     * Show the form for editing the specified election.
     */
    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    /**
     * Update the specified election.
     */
    public function update(Request $request, Election $election)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date_format:Y-m-d\TH:i',
            'end_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:start_date',
        ]);

        $election->update($request->only([
            'title',
            'description',
            'start_date',
            'end_date',
        ]));

        return redirect()
            ->route('admin.elections.index')
            ->with('success', 'Election updated successfully.');
    }

    /**
     * Remove the specified election.
     */
    public function destroy(Election $election)
    {
        $election->delete();

        return redirect()
            ->route('admin.elections.index')
            ->with('success', 'Election deleted successfully.');
    }

    /**
     * Show election results with winners, ties, and total votes.
     */
    public function results(Request $request)
    {
        $now = Carbon::now();
        $filter = $request->get('status');

        $query = Election::with(['candidates' => fn($q) => $q->withCount('votes')]);

        if ($filter === 'active') {
            $query->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now);
        } elseif ($filter === 'upcoming') {
            $query->where('start_date', '>', $now);
        } elseif ($filter === 'closed') {
            $query->where('end_date', '<', $now);
        }

        $elections = $query->orderBy('start_date', 'asc')->get();

        foreach ($elections as $election) {
            $candidates = $election->candidates ?? collect();

            // Total votes for this election
            $election->total_votes = (int) $candidates->sum(fn($c) => $c->votes_count ?? $c->votes()->count());

            // Determine winners
            if ($election->total_votes > 0) {
                $maxVotes = $candidates->max(fn($c) => $c->votes_count ?? $c->votes()->count());
                $election->winners = $candidates
                    ->filter(fn($c) => ($c->votes_count ?? $c->votes()->count()) === $maxVotes)
                    ->values();
            } else {
                $election->winners = collect();
            }
        }

        return view('admin.results.index', compact('elections', 'filter'));
    }
}
