<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show all notifications (role & user based)
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->pluck('name')->first(); // Agar multiple roles ho to adjust karo

        $notifications = Notification::where(function($q) use ($user, $role) {
            $q->where('user_id', $user->id)
              ->orWhere(function($q2) use ($role) {
                  $q2->whereNotNull('role')->where('role', $role);
              });
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return view('notifications.index', compact('notifications'));
    }

    // Mark as read
    public function markAsRead($id)
    {
        $notification = $this->findNotification($id);
        $notification->update(['is_read' => true]);

        return back();
    }

    // Toggle read/unread
    public function toggleRead($id)
    {
        $notification = $this->findNotification($id);
        $notification->update(['is_read' => ! $notification->is_read]);

        return back();
    }

    // Delete
    public function destroy($id)
    {
        $notification = $this->findNotification($id);
        $notification->delete();

        return back();
    }

    // Unread count (for badge)
    public function unreadCount()
    {
        $user = Auth::user();
        $role = $user->roles->pluck('name')->first();

        $count = Notification::where(function($q) use ($user, $role) {
            $q->where('user_id', $user->id)
              ->orWhere(function($q2) use ($role) {
                  $q2->whereNotNull('role')->where('role', $role);
              });
        })
        ->where('is_read', false)
        ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Helper function for DRY principle
     */
    protected function findNotification($id)
    {
        $user = Auth::user();
        $role = $user->roles->pluck('name')->first();

        $notification = Notification::findOrFail($id);

        if ($notification->user_id && $notification->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if ($notification->role && $notification->role !== $role) {
            abort(403, 'Unauthorized');
        }

        return $notification;
    }
}
