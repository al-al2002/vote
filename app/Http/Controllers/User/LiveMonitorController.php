<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Election;

class LiveMonitorController extends Controller
{
    public function index()
    {
        // Fetch only active elections with candidates and votes count
        $activeElections = Election::with(['candidates' => function ($q) {
                $q->withCount('votes');
            }])
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->get();

        return view('user.livemonitor.index', compact('activeElections'));
    }
}
