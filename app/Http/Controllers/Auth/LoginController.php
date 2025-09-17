<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect user after login based on role.
     */
    protected function authenticated(Request $request, $user)
    {
        // ✅ Check agar admin ne user ko inactive kiya hai to login block ho
        if (!$user->status) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account is inactive. Please contact the administrator.',
            ]);
        }

        // ✅ Notification save karo
        Notification::create([
            'user_id' => $user->id,
            'role'    => $user->roles->pluck('name')->first(), 
            'type'    => 'login',
            'message' => "You have logged in successfully.",
            'is_read' => false,
        ]);

        return redirect()->route('admin.dashboard');
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