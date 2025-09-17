<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    /**
     * List voters with optional eligibility filter.
     */
  public function index(Request $request)
{
    $query = User::where('role', 'voter');

    // Filter if selected
    if ($request->filled('eligible') && in_array($request->eligible, ['0', '1'])) {
        $query->where('is_eligible', $request->eligible);
    }

    $voters = $query->paginate(10);

    return view('admin.voters.index', compact('voters'));
}



    /**
     * Toggle voter eligibility manually.
     */
  public function toggle($id)
{
    $voter = User::findOrFail($id);

    if ($voter->role !== 'voter') {
        return redirect()->back()->with('error', 'Only voters can be updated.');
    }

    $voter->is_eligible = !$voter->is_eligible;
    $voter->save();

    return redirect()->back()->with('success', 'Voter status updated successfully.');
}


}
