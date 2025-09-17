<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        return view('user.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        // ✅ Always fetch as Eloquent model
        $user = User::find(Auth::id());

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // ✅ Validation
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ Assign values
        $user->name  = $request->name;
        $user->email = $request->email;

        // ✅ Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if it exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Store new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
