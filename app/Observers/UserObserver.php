<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Notification;

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