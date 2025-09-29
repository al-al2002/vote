<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;

class ResultController extends Controller
{
    public function index()
    {
        $now = now();

        // Fetch only closed elections with candidates and their votes count
        $elections = Election::with(['candidates' => function ($q) {
                $q->withCount('votes');
            }])
            ->where('end_date', '<', $now) // Only closed elections
            ->orderBy('end_date', 'desc')
            ->get();

        return view('admin.results.index', compact('elections'));
    }
}
