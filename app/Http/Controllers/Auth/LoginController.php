<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect user after login based on role.
     */
    protected function authenticated(Request $request, $user)
    {

      Notification::create([
        'user_id' => $user->id,
        'role'    => $user->roles->pluck('name')->first(), // role automatically uth jayega
        'type'    => 'login',
        'message' => "You logged in",
        'is_read' => false,
    ]);
        // Agar user ke pass role hi nahi hai
        if ($user->roles->isEmpty()) {
            return redirect()->route('no.role'); // Pending approval page
        }

        // Ab chahe koi bhi role ho → sabko admin.dashboard par bhej do
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