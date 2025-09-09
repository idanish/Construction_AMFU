<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        // Profile Picture Upload
        if ($request->hasFile('profile_picture')) {
            $fileName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->move(public_path('uploads/profile_pictures'), $fileName);
            $user->profile_picture = $fileName;
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

        $user->save();

        return redirect()->route('profile.settings')->with('success', 'Profile updated successfully!');
    }
}
