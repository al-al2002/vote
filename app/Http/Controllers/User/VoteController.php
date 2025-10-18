<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Vote;

class VoteController extends Controller
{
    public function downloadPDF($electionId)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Get votes for this user and this election
        $votes = Vote::where('user_id', $user->id)
            ->where('election_id', $electionId)
            ->with(['election', 'candidate'])
            ->get();

        if ($votes->isEmpty()) {
            return redirect()->back()->with('error', 'You have no votes recorded for this election.');
        }

        // Embed the logo as base64 to avoid GD/Imagick requirement
        $logoPath = public_path('images/votemaster.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $pdf = Pdf::loadView('user.votes.receipt', [
            'user' => $user,
            'votes' => $votes,
            'logoBase64' => $logoBase64,
        ]);

        $fileName = 'Vote_Receipt_' . $user->id . '_Election_' . $electionId . '.pdf';
        return $pdf->download($fileName);
    }
}
