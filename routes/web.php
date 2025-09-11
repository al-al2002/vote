<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\AdminDashboardController;
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
// Admin Dashboard + Admin Routes
// ----------------------
Route::middleware(['auth', 'isAdmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Manage Elections
        Route::resource('elections', ElectionController::class);

        // Manage Voters
        Route::get('/voters', [VoterController::class, 'index'])->name('voters');
        Route::patch('/voters/{user}/toggle', [VoterController::class, 'toggleEligibility'])->name('voters.toggle');
    });

//candidates
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('candidates', App\Http\Controllers\Admin\CandidateController::class);
});
Route::resource('candidates', App\Http\Controllers\Admin\CandidateController::class);



// ----------------------
// User Dashboard (auth only, not admin)
// ----------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});
