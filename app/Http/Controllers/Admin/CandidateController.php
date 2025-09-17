<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CandidateController extends Controller
{
    /**
     * Display a listing of candidates with optional election filter.
     */
  public function index(Request $request)
{
    $elections = Election::all(); // For dropdown
    $query = Candidate::with('election');

    // Filter by election status
    if ($request->filled('status')) {
        $status = $request->status;
        $now = now();

        $query->whereHas('election', function($q) use ($status, $now) {
            if ($status === 'active') {
                $q->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now);
            } elseif ($status === 'upcoming') {
                $q->where('start_date', '>', $now);
            } elseif ($status === 'closed') {
                $q->where('end_date', '<', $now);
            }
        });
    }

    // Filter by specific election
    if ($request->filled('election_id')) {
        $query->where('election_id', $request->election_id);
    }

    $candidates = $query->get();

    return view('admin.candidates.index', compact('candidates', 'elections'));
}

    /**
     * Show the form for creating a new candidate.
     */
    public function create()
    {
        // Only elections that are not closed
        $elections = Election::where('end_date', '>', Carbon::now())->get();
        return view('admin.candidates.create', compact('elections'));
    }

    /**
     * Store a newly created candidate in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'position'    => 'required|string|max:255',
            'election_id' => 'required|exists:elections,id',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $election = Election::findOrFail($request->election_id);

        if (Carbon::now()->gt($election->end_date)) {
            return redirect()->back()->with('error', 'Cannot add candidate to a closed election.');
        }

        $data = $request->only(['name', 'position', 'election_id']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        Candidate::create($data);

        return redirect()->route('admin.candidates.index')
                         ->with('delete_success', 'Candidate added successfully!');
    }

    /**
     * Show the form for editing the specified candidate.
     */
    public function edit(Candidate $candidate)
    {
        $elections = Election::all();
        return view('admin.candidates.edit', compact('candidate', 'elections'));
    }

    /**
     * Update the specified candidate in storage.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'position'    => 'required|string|max:255',
            'election_id' => 'required|exists:elections,id',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $election = Election::findOrFail($request->election_id);

        if (Carbon::now()->gt($election->end_date)) {
            return redirect()->back()->with('error', 'Cannot assign candidate to a closed election.');
        }

        $data = $request->only(['name', 'position', 'election_id']);

        // Replace photo if uploaded
        if ($request->hasFile('photo')) {
            if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
                Storage::disk('public')->delete($candidate->photo);
            }
            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        $candidate->update($data);

        return redirect()->route('admin.candidates.index')
                         ->with('delete_success', 'Candidate updated successfully!');
    }

    /**
     * Remove the specified candidate from storage.
     */
    public function destroy(Candidate $candidate)
    {
        if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $candidate->delete();

        return redirect()->route('admin.candidates.index')
                         ->with('delete_success', 'Candidate deleted successfully!');
    }
}
