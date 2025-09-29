<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class VoterController extends Controller
{
    // List voters with filter
    public function index(Request $request)
    {
        $query = User::where('role', 'voter')
          ->orderBy('created_at', 'DESC');

        $voters = $query->get()->filter(function ($voter) use ($request) {
            if ($request->filter === 'eligible') {
                return $voter->finalEligibility();
            } elseif ($request->filter === 'not_eligible') {
                return !$voter->finalEligibility();
            }
            return true;
        });

        // Manual pagination
        $page = $request->get('page', 1);
        $perPage = 10;
        $paginated = new LengthAwarePaginator(
            $voters->forPage($page, $perPage),
            $voters->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('admin.voters.index', [
            'voters' => $paginated,
        ]);
    }

    // Toggle voter eligibility (admin override)
    public function toggleEligibility($id)
    {
        $voter = User::findOrFail($id);

        // Flip only the override status
        $newStatus = !$voter->is_eligible;

        $voter->overrideEligibility($newStatus);

        return redirect()->back()->with('success', 'Voter eligibility updated successfully!');
    }
}
