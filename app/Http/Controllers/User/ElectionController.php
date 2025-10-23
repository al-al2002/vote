<?php

namespace App\Http\Controllers\User;

use App\Models\Vote;
use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ElectionController extends Controller
{
    /**
     * Show all elections.
     */
    public function index()
    {
        $elections = Election::with('candidates')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('user.elections.index', compact('elections'));
    }

    /**
     * Show a specific election and the user's votes.
     */
    public function show(Election $election)
    {
        $user = Auth::user();

        // Fetch all votes the user cast in this election
        $userVotes = Vote::where('user_id', $user->id)
                        ->where('election_id', $election->id)
                        ->pluck('candidate_id')
                        ->toArray();

        return view('user.elections.show', [
            'election'   => $election,
            'candidates' => $election->candidates,
            'userVotes'  => $userVotes,
        ]);
    }

    /**
     * Cast votes — one per position per election.
     */
    public function vote(Request $request, Election $election)
    {
        $user = Auth::user();

        $request->validate([
            'candidate_ids' => 'required|array|min:1',
            'candidate_ids.*' => 'exists:candidates,id',
        ]);

        // Check if user already voted in this election
        $existingVotes = Vote::where('user_id', $user->id)
                            ->where('election_id', $election->id)
                            ->exists();

        if ($existingVotes) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted in this election.'
            ]);
        }

        // Enforce one vote per position
        $selectedCandidates = \App\Models\Candidate::whereIn('id', $request->candidate_ids)->get();

        $positionsVoted = [];

        foreach ($selectedCandidates as $candidate) {
            if (in_array($candidate->position, $positionsVoted)) {
                continue; // skip duplicates for same position
            }

            Vote::create([
                'user_id'      => $user->id,
                'election_id'  => $election->id,
                'candidate_id' => $candidate->id,
            ]);

            $positionsVoted[] = $candidate->position;
        }

        return response()->json([
            'success' => true,
            'message' => 'Your votes have been successfully cast!'
        ]);
    }
}
