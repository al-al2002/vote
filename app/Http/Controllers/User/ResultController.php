<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ResultController extends Controller
{
    public function index()
    {
        $today = Carbon::now();

        // Load candidates with votes_count
        $elections = Election::with(['candidates' => function ($q) {
            $q->withCount('votes');
        }])
        ->orderBy('start_date', 'desc')
        ->get();

        // Compute total votes + winners
        foreach ($elections as $election) {
            $candidates = $election->candidates ?? collect();

            $election->total_votes = (int) $candidates->sum('votes_count');

            if ($election->total_votes > 0) {
                $maxVotes = $candidates->max('votes_count');
                $election->winners = $candidates
                    ->filter(fn($c) => $c->votes_count === $maxVotes)
                    ->values();
            } else {
                $election->winners = collect();
            }
        }

        return view('user.results.index', compact('elections'));
    }
}
