<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ----------------------
// Controllers
// ----------------------
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ElectionController as AdminElectionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\LiveMonitorController;

// User
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ElectionController as UserElectionController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\PasswordController;
use App\Http\Controllers\User\VoteHistoryController;
use App\Http\Controllers\User\ResultController as UserResultController;

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
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Elections CRUD
    Route::resource('elections', AdminElectionController::class);

    // Candidates CRUD
    Route::resource('candidates', CandidateController::class);

    // Voters
    Route::get('/voters', [VoterController::class, 'index'])->name('voters.index');
    Route::patch('/voters/{voter}/toggle', [VoterController::class, 'toggle'])->name('voters.toggle');

    // Results
    Route::get('/results', [ResultController::class, 'index'])->name('results');
});

// ----------------------
// User Routes
// ----------------------
Route::prefix('user')->name('user.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Elections
    Route::get('/elections', [UserElectionController::class, 'index'])->name('elections.index');
    Route::get('/elections/{election}', [UserElectionController::class, 'show'])->name('elections.show');
    Route::post('/elections/{election}/vote', [UserElectionController::class, 'vote'])->name('elections.vote');

    // Results
    Route::get('/results', [UserResultController::class, 'index'])->name('results.index');

    // Vote History (AJAX)
    Route::get('/voting-history', function (Request $request) {
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
    })->name('voting.history');
});

// ----------------------
// Profile & Settings Routes
// ----------------------
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Settings page
    Route::get('/profile/settings', function () {
        return view('user.profile.settings');
    })->name('profile.settings');

    // Change Password
    Route::get('/profile/password/change', [PasswordController::class, 'edit'])->name('password.change');
    Route::post('/profile/password/change', [PasswordController::class, 'update'])->name('password.update');
});

// ----------------------
// Additional User Vote History Route (AJAX endpoint)
// ----------------------
Route::middleware(['auth'])->get('/user/votes/history', [VoteHistoryController::class, 'fetch'])->name('user.votes.history');



Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/voters', [VoterController::class, 'index'])->name('voters.index');
    Route::patch('/voters/{voter}/toggle', [VoterController::class, 'toggle'])->name('voters.toggle');
});

//admin live monitor
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/live-monitor', [LiveMonitorController::class, 'index'])
        ->name('live-monitor');

    Route::get('/live-monitor/data', [LiveMonitorController::class, 'data'])
        ->name('live-monitor.data');
});

//user live monitor
Route::get('/user/live-monitor', [App\Http\Controllers\User\LiveMonitorController::class, 'index'])
    ->name('user.live-monitor');



