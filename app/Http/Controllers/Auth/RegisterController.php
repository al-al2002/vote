<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
  public function register(Request $request)
{
    // Validate input
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'voter_id' => ['required', 'string', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
        'agree' => ['accepted'],
    ], [
        'password.confirmed' => 'Password and Confirm Password do not match.',
        'agree.accepted' => 'You must agree to the terms and conditions.',
    ]);

    // Create user with default role 'voter' and skipped_elections = 0
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'voter_id' => $request->voter_id,
        'password' => Hash::make($request->password),
        'role' => 'voter',
        'skipped_elections' => 0, // ✅ new accounts start at zero
    ]);

    // Log in the user automatically
    Auth::login($user);

    // Redirect to dashboard based on role
    return $user->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.dashboard');
}

}
