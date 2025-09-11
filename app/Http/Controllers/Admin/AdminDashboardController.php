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

        // Counts only
        $activeElections = Election::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->count();

        $upcomingElections = Election::where('start_date', '>', $now)->count();

        $closedElections = Election::where('end_date', '<', $now)->count();

        $totalVoters = User::where('role', 'voter')->count();

        return view('admin.dashboard', compact(
            'activeElections',
            'upcomingElections',
            'closedElections',
            'totalVoters'
        ));
    }
}
