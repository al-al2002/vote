<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ElectionController as AdminElectionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\User\ElectionController as UserElectionController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Admin\ElectionController;

// ----------------------
// Authentication Routes
// ----------------------
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ----------------------
// Admin Routes
// ----------------------
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Elections CRUD
    Route::resource('elections', AdminElectionController::class);

    // Candidates CRUD
    Route::resource('candidates', CandidateController::class);

    // Voters Management
    Route::get('/voters', [VoterController::class, 'index'])->name('voters');
    Route::patch('/voters/{user}/toggle', [VoterController::class, 'toggleEligibility'])->name('voters.toggle');

    // Results
    Route::get('/results', [ResultController::class, 'index'])->name('results');
});

// ----------------------
// User Routes
// ----------------------
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Elections
    Route::get('/elections', [UserElectionController::class, 'index'])->name('elections.index');
    Route::get('/elections/{election}', [UserElectionController::class, 'show'])->name('elections.show');
    Route::post('/elections/{election}/vote', [UserElectionController::class, 'vote'])->name('elections.vote');
});
Route::middleware(['auth','isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    // ... other admin routes

    // results page
    Route::get('/results', [ElectionController::class, 'results'])->name('results');
});

Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::get('/results', [App\Http\Controllers\User\ResultController::class, 'index'])
        ->name('user.results.index');
});


Route::get('/user/voting-history', function (Request $request) {
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'error' => 'Not logged in',
            'votes' => []
        ], 401);
    }

    return response()->json([
        'votes' => $user->votes()->with('election')->latest()->get()
    ]);
})->name('user.voting.history')->middleware('auth');

Route::patch('/admin/voters/{id}/toggle', [App\Http\Controllers\Admin\VoterController::class, 'toggleEligibility'])
    ->name('admin.voters.toggle');
