<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status'); // active / closed / null
        $now = Carbon::now();

        // Stats
        $activeElections = Election::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->when($statusFilter && $statusFilter !== 'active', fn($q) => $q->whereRaw('0'), fn($q) => $q)
            ->count();

        $closedElections = Election::where('end_date', '<', $now)
            ->when($statusFilter && $statusFilter !== 'closed', fn($q) => $q->whereRaw('0'), fn($q) => $q)
            ->count();

        $upcomingElections = Election::where('start_date', '>', $now)
            ->when($statusFilter && $statusFilter !== 'upcoming', fn($q) => $q->whereRaw('0'), fn($q) => $q)
            ->count();

        $totalVoters = User::where('role', 'voter')->count();

        // Chart data (active + closed elections)
        $elections = Election::withCount('votes')
            ->when($statusFilter === 'active', fn($q) => $q->where('start_date', '<=', $now)->where('end_date', '>=', $now))
            ->when($statusFilter === 'closed', fn($q) => $q->where('end_date', '<', $now))
            ->get();

        $chartLabels = $elections->pluck('title');
        $chartData = $elections->pluck('votes_count');

        return view('admin.dashboard', compact(
            'activeElections',
            'closedElections',
            'upcomingElections', // <--- pass to the view
            'totalVoters',
            'chartLabels',
            'chartData'
        ));
    }
}
