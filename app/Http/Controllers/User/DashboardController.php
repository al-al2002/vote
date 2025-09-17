<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Active elections: started and not ended
        $activeElectionsList = Election::where('start_date', '<=', $now)
                                       ->where('end_date', '>=', $now)
                                       ->get();
        $activeElections = $activeElectionsList->count();

        // Upcoming elections: start date is in the future
        $upcomingElectionsList = Election::where('start_date', '>', $now)->get();
        $upcomingElections = $upcomingElectionsList->count();

        // Closed elections: end date is in the past
        $closedElectionsList = Election::where('end_date', '<', $now)->get();
        $closedElections = $closedElectionsList->count();

        return view('user.dashboard', compact(
            'activeElectionsList',
            'upcomingElectionsList',
            'closedElectionsList',
            'activeElections',
            'upcomingElections',
            'closedElections'
        ));
    }
}
