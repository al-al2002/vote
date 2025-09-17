<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('user.profile.change-password');
    }

 public function update(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::findOrFail(Auth::id()); // ensure Eloquent User

    // Check current password
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    // Update password
    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->route('profile.edit')->with('success', 'Password updated successfully.');
}

}
