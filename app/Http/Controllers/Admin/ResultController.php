<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Election;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->filled('status') ? $request->status : null;
        $now = now();

        // Base query with candidates and votes count
        $query = Election::with(['candidates' => fn($q) => $q->withCount('votes')]);

        // Apply status filter if provided
        if ($statusFilter) {
            if ($statusFilter === 'active') {
                $query->where('start_date', '<=', $now)
                      ->where('end_date', '>=', $now);
            } elseif ($statusFilter === 'upcoming') {
                $query->where('start_date', '>', $now);
            } elseif ($statusFilter === 'closed') {
                $query->where('end_date', '<', $now);
            }
        }

        $elections = $query->get();

        // Pass current filter to the view for dropdown selection
        return view('admin.results.index', compact('elections', 'statusFilter'));
    }
}
