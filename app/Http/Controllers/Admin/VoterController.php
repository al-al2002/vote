<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    /**
     * Display voters with pagination.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'voter'); // ✅ only voters

        if ($request->has('eligible') && $request->eligible !== '') {
            $eligible = $request->eligible == 1;

            // ✅ Need collection here because finalEligibility() is computed
            $voters = $query->get()->filter(function ($voter) use ($eligible) {
                return $voter->finalEligibility() == $eligible;
            });

            // ✅ Manually paginate
            $voters = $voters->forPage($request->get('page', 1), 10);
        } else {
            $voters = $query->paginate(10);
        }

        return view('admin.voters.index', [
            'voters' => $voters
        ]);
    }

    /**
     * Toggle voter eligibility (manual override).
     */
    public function toggleEligibility($id)
    {
        $voter = User::findOrFail($id);

        if (is_null($voter->admin_override)) {
            // First time → flip whatever auto says
            $voter->admin_override = !$voter->finalEligibility();
        } else {
            // Flip admin override
            $voter->admin_override = !$voter->admin_override;
        }

        $voter->save();

        return response()->json([
            'success' => true,
            'message' => 'Voter eligibility updated successfully!'
        ]);
    }

    public function markEligible($id)
    {
        $voter = User::findOrFail($id);
        $voter->manual_eligibility = 1;
        $voter->save();

        return redirect()->back()->with('success', 'Voter marked as eligible.');
    }

    public function markNotEligible($id)
    {
        $voter = User::findOrFail($id);
        $voter->manual_eligibility = 0;
        $voter->save();

        return redirect()->back()->with('success', 'Voter marked as not eligible.');
    }

    public function resetEligibility($id)
    {
        $voter = User::findOrFail($id);
        $voter->manual_eligibility = null;
        $voter->admin_override = null; // ✅ reset both
        $voter->save();

        return redirect()->back()->with('success', 'Voter eligibility reset to automatic rules.');
    }
}
