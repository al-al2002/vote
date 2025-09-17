<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Counts
        $activeElections = Election::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->count();

        $upcomingElections = Election::where('start_date', '>', $now)->count();

        $closedElections = Election::where('end_date', '<', $now)->count();

        $totalVoters = User::where('role', 'voter')->count();

        // Dynamic chart data: fetch all elections with vote counts
        $elections = Election::withCount('votes')->get();
        $chartLabels = $elections->pluck('title'); // Election names
        $chartData = $elections->pluck('votes_count'); // Votes per election

        return view('admin.dashboard', compact(
            'activeElections',
            'upcomingElections',
            'closedElections',
            'totalVoters',
            'chartLabels',
            'chartData'
        ));
    }
}
