<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Election;


class ResultController extends Controller
{
     public function index()
    {
        $elections = Election::with('candidates.votes')->get();

        return view('admin.results.index', compact('elections'));
    }
}
