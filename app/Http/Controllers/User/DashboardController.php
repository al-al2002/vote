<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'User not logged in.');
        }

        $now = Carbon::now('Asia/Manila');

        // Active elections: started but not ended
        $activeElectionsList = Election::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();

        // Upcoming elections: start date in the future
        $upcomingElectionsList = Election::where('start_date', '>', $now)->get();

        // Closed elections: end date in the past
        $closedElectionsList = Election::where('end_date', '<', $now)->get();

        // Skipped elections: ended but user did not vote
        $userVoteIds = $user->votes->pluck('election_id')->toArray();
        $skippedElectionsList = Election::where('end_date', '<', $now)
            ->whereNotIn('id', $userVoteIds)
            ->get();

        return view('user.dashboard', [
            'user' => $user,
            'activeElectionsList' => $activeElectionsList,
            'upcomingElectionsList' => $upcomingElectionsList,
            'closedElectionsList' => $closedElectionsList,
            'skippedElectionsList' => $skippedElectionsList,
            'activeElections' => $activeElectionsList->count(),
            'upcomingElections' => $upcomingElectionsList->count(),
            'closedElections' => $closedElectionsList->count(),
            'skippedElections' => $skippedElectionsList->count(),
            'userVotesCount' => $user->votes->count(),
        ]);
    }
}
