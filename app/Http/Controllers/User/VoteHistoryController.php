<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VoteHistoryController extends Controller
{
    public function fetch()
    {
        $user = Auth::user();

        if (!$user || !($user instanceof User)) {
            return '<li class="text-red-500">User not authenticated or invalid.</li>';
        }

        if (!method_exists($user, 'votes')) {
            return '<li class="text-red-500">User model has no votes() method.</li>';
        }

      $votes = $user->votes()->with('election')->latest()->get();
$html = '';
foreach ($votes as $vote) {
    $electionTitle = $vote->election ? $vote->election->title : 'Unknown Election';
    $html .= '<li>Voted in <span class="font-semibold">' . $electionTitle . '</span> on ' . $vote->created_at->format('M d, Y h:i:s A') . '</li>';
}
return $html;

    }
}
