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
     * Display a listing of candidates with optional election filter (AJAX ready).
     */
    public function index(Request $request)
    {
        $elections = Election::all();
        $query = Candidate::with('election');

        if ($request->filled('election_id')) {
            $query->where('election_id', $request->election_id);
        }

        $candidates = $query->get();

        if ($request->ajax()) {
            return view('admin.candidates.partials.candidates_table', compact('candidates'))->render();
        }

        return view('admin.candidates.index', compact('candidates', 'elections'));
    }

    /**
     * Show the form for creating a new candidate.
     */
    public function create()
    {
        $elections = Election::where('end_date', '>', Carbon::now())->get();
        return view('admin.candidates.create', compact('elections'));
    }

    /**
     * Store a newly created candidate in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'election_id' => 'required|exists:elections,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate added successfully.');
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
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'election_id' => 'required|exists:elections,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $election = Election::findOrFail($request->election_id);

        if (Carbon::now()->gt($election->end_date)) {
            return redirect()->back()->with('error', 'Cannot assign candidate to a closed election.');
        }

        $data = $request->only(['name', 'position', 'election_id']);

        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
                Storage::disk('public')->delete($candidate->photo);
            }
            // Store new photo
            $data['photo'] = $request->file('photo')->store('candidates', 'public');
        }

        $candidate->update($data);

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate updated successfully.');
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

        return redirect()->route('admin.candidates.index')->with('success', 'Candidate deleted successfully.');
    }
}
