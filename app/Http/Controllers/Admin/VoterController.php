<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    // Show all voters
    public function index()
    {
        $voters = User::where('role', 'voter')->paginate(10);
        return view('admin.voters', compact('voters'));
    }

    // Toggle voter eligibility
    public function toggleEligibility(User $user)
    {
        if ($user->role !== 'voter') {
            return redirect()->back()->with('error', 'Only voters can be updated.');
        }

        $user->is_eligible = !$user->is_eligible;
        $user->save();

        return redirect()->back()->with('success', 'Voter status updated successfully.');
    }
}
