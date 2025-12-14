<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    // Show Profile Page
    public function edit()
    {
        return view('profile.settings');
    }

    // Update Profile
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Update Name & Email
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profile_picture')) {
            // delete previous image if exists
            try {
                if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
            } catch (\Exception $e) {
                // ignore deletion errors
            }

            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

        // Update Password (if provided)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);

            // Yahan admin ko notify karna hai
            $admin = User::whereHas('roles', function ($q) {
                $q->where('name', 'Admin');
            })->first();

            if ($admin) {
                // For example email bhej sakte ho
                \Mail::raw("User {$user->name} ne apna password change kar diya hai.", function ($msg) use ($admin) {
                    $msg->to($admin->email)->subject('Password Change Notification');
                });
            }
        }

        try {
            $user->save();
            // Refresh the authenticated user so changes show immediately in views
            Auth::setUser($user);
            if (isset($path)) {
                \Log::info('Profile picture updated', ['user_id' => $user->id, 'path' => $path]);
            } else {
                \Log::info('Profile updated without picture', ['user_id' => $user->id]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed saving profile: ' . $e->getMessage(), ['user_id' => $user->id]);
            return redirect()->route('profile.settings')->with('error', 'Failed to update profile.');
        }

        return redirect()->route('profile.settings')->with('success', 'Profile updated successfully!');
    }
}
