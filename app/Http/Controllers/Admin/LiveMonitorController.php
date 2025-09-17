<?php
namespace App\Http\Controllers\Admin;
use App\Models\Election;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;

class LiveMonitorController extends Controller
{
   public function index()
{
    $activeElections = Election::where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->with(['candidates' => function ($query) {
            $query->withCount('votes');
        }])
        ->get();

    return view('admin.livemonitor.index', compact('activeElections'));
}



    public function data()
    {
        $candidates = Candidate::with('election')
            ->withCount('votes')
            ->get();

        $data = $candidates->map(function ($c) {
            return [
                'candidate' => $c->name,
                'election' => $c->election->title,
                'votes' => $c->votes_count,
            ];
        });

        return response()->json($data);
    }
}
