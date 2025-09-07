<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;   // ye line add karni zaroori hai
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers; // ye trait include karna hai

    /**
     * Redirect user after login based on role.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->roles->isEmpty()) {
            return redirect()->route('no.role'); // Pending approval page
        }

        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('PM')) {
            return redirect()->route('pm.dashboard');
        } elseif ($user->hasRole('FCO')) {
            return redirect()->route('fco.dashboard');
        } elseif ($user->hasRole('PMO')) {
            return redirect()->route('pmo.dashboard');
        } elseif ($user->hasRole('CSO')) {
            return redirect()->route('cso.dashboard');
        }

        return redirect('/');
    }

    /**
     * Logout and redirect.
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }
}
