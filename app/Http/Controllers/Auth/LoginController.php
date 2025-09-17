<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate login fields
        $request->validate([
            'login' => ['required', 'string'], // can be email or voter_id
            'password' => ['required'],
        ]);

        $loginInput = $request->input('login');

        // Check if input is email or voter_id
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'voter_id';

        $credentials = [
            $field => $loginInput,
            'password' => $request->password,
        ];

        // Attempt login
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        return back()->withErrors([
            'login' => 'Invalid credentials.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
