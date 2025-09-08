<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Count only users with role = 'user'
        $totalVoters = User::where('role', 'user')->count();

        return view('admin.dashboard', compact('totalVoters'));
    }
}
