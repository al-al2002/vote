<?php

use Illuminate\Support\Facades\Route;

// ----------------------
// Controllers
// ----------------------
// Auth
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
use App\Http\Controllers\User\LiveMonitorController as UserLiveMonitorController;

// ----------------------
// Redirect root URL to login
// ----------------------
Route::get('/', fn() => redirect()->route('login'));

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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Elections CRUD
    Route::resource('elections', AdminElectionController::class);

    // Candidates CRUD
    Route::resource('candidates', CandidateController::class);
    Route::post('/candidates/store-multiple', [CandidateController::class, 'storeMultiple'])
        ->name('candidates.storeMultiple');

    // Voters
    Route::get('/voters', [VoterController::class, 'index'])->name('voters.index');
    Route::patch('/voters/{voter}/toggle', [VoterController::class, 'toggle'])->name('voters.toggle');

    // Results
    Route::get('/results', [ResultController::class, 'index'])->name('results');

    // Live Monitor
    Route::get('/live-monitor', [LiveMonitorController::class, 'index'])->name('live-monitor');
    Route::get('/live-monitor/data', [LiveMonitorController::class, 'data'])->name('live-monitor.data');
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

    // Vote History (AJAX endpoint)
    Route::get('/votes/history', [VoteHistoryController::class, 'fetch'])->name('votes.history');

    // Live Monitor
    Route::get('/live-monitor', [UserLiveMonitorController::class, 'index'])->name('live-monitor');
});

// ----------------------
// Profile & Settings
// ----------------------
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Settings page
    Route::get('/profile/settings', fn() => view('user.profile.settings'))->name('profile.settings');

    // Change Password
    Route::get('/profile/password/change', [PasswordController::class, 'edit'])->name('password.change');
    Route::post('/profile/password/change', [PasswordController::class, 'update'])->name('password.update');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/voters', [VoterController::class, 'index'])->name('voters.index');
    Route::patch('/voters/{id}/toggle', [VoterController::class, 'toggle'])->name('voters.toggle');
});
