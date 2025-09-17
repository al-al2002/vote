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
     * Display a listing of elections with their candidates.
     */
    public function index()
    {
        $elections = Election::with('candidates')->get();
        return view('user.elections.index', compact('elections'));
    }

    /**
     * Display a specific election and the user's vote (if exists).
     */
    public function show(Election $election)
    {
        $user = Auth::user();

        $userVote = Vote::where('user_id', $user->id)
                        ->where('election_id', $election->id)
                        ->first();

        return view('user.elections.show', [
            'election'   => $election,
            'candidates' => $election->candidates,
            'userVote'   => $userVote,
        ]);
    }

    /**
     * Cast a vote for a candidate in an election.
     */
   public function vote(Request $request, Election $election)
{
    $user = Auth::user();

    // Check if already voted
    $existingVote = Vote::where('user_id', $user->id)
                        ->where('election_id', $election->id)
                        ->first();

    if ($existingVote) {
        return redirect()->route('user.dashboard')
                         ->with('error', 'You have already voted in this election.');
    }

    // Save vote
    Vote::create([
        'user_id' => $user->id,
        'election_id' => $election->id,
        'candidate_id' => $request->candidate_id,
    ]);

    // Redirect to dashboard with success
    return redirect()->route('user.dashboard')
                     ->with('success', 'Your vote has been cast!');
}

}
