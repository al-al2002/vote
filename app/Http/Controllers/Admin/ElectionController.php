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
        $today = Carbon::now();
        $filter = $request->get('status');
        $keyword = $request->get('search');

        $query = Election::query();

        // 🔍 Search by election title
        if (!empty($keyword)) {
            $query->where('title', 'like', "%{$keyword}%");
        }

        // 📌 Apply filter based on status
        if ($filter === 'active') {
            $query->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today);
        } elseif ($filter === 'upcoming') {
            $query->where('start_date', '>', $today);
        } elseif ($filter === 'closed') {
            $query->where('end_date', '<', $today);
        }

        $elections = $query->orderBy('start_date', 'desc')->get();

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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
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
        $today = Carbon::now();

        $filter = $request->get('status');

        // eager-load candidates with votes_count
        $query = Election::with([
            'candidates' => function ($q) {
                $q->withCount('votes');
            }
        ]);

        // apply dropdown status filter
        if ($filter === 'active') {
            $query->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today);
        } elseif ($filter === 'upcoming') {
            $query->where('start_date', '>', $today);
        } elseif ($filter === 'closed') {
            $query->where('end_date', '<', $today);
        }

        $elections = $query->orderBy('start_date', 'desc')->get();

        // compute total_votes and winners safely
        foreach ($elections as $election) {
            $candidates = $election->candidates ?? collect();

            // total votes (use votes_count if available, otherwise count())
            $election->total_votes = (int) $candidates->sum(function ($c) {
                return $c->votes_count ?? $c->votes()->count();
            });

            if ($election->total_votes > 0) {
                // compute max votes (use votes_count if present)
                $maxVotes = $candidates->max(function ($c) {
                    return $c->votes_count ?? $c->votes()->count();
                });

                $election->winners = $candidates
                    ->filter(function ($c) use ($maxVotes) {
                        return (($c->votes_count ?? $c->votes()->count()) === $maxVotes);
                    })
                    ->values();
            } else {
                $election->winners = collect();
            }
        }

        // IMPORTANT: return the view that exists at resources/views/admin/results/index.blade.php
        return view('admin.results.index', compact('elections', 'filter'));
    }
}
