<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeNotification;

class UserObserver
{
    // Jab naya user create ho
    public function created(User $user)
    {
        Notification::create([
            'user_id' => $user->id,
            'role'    => null, // sirf user ke liye
            'type'    => 'create',
            'message' => 'your account has been created.',
            'is_read' => false,
        ]);

        // Send welcome email to new user
        try {
            $loginUrl = url('/login');
            $temporaryPassword = null;

            if ($user->email) {
                Mail::to($user->email)->send(new WelcomeNotification(
                    $user->name,
                    $user->email,
                    $loginUrl,
                    $temporaryPassword
                ));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email to user ' . $user->email . ': ' . $e->getMessage());
        }
    }
    // Jab user update ho
    public function updated(User $user)
    {
        Notification::create([
            'user_id' => $user->id,
            'role'    => null,
            'type'    => 'update',
            'message' => 'Your account details have been updated.',
            'is_read' => false,
        ]);
    }

    // Jab user delete ho
    public function deleted(User $user)
    {
        Notification::create([
            'user_id' => $user->id,
            'role'    => null,
            'type'    => 'delete',
            'message' => 'your account has been deleted.',
            'is_read' => false,
        ]);
    }
}