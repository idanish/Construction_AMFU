<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;

// Public API route
Route::get('/status', function () {
    return response()->json(['status' => 'API is working ✅']);
});

// Protected routes with sanctum auth
Route::middleware(['auth:sanctum'])->group(function () {

    // sirf Admin role wale access karenge
    Route::middleware('role:Admin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('api.users.index');
    });

    // sirf User role wale access karenge
    Route::middleware('role:User')->group(function () {
        Route::get('/profile', function () {
            return response()->json(['message' => 'This is user profile endpoint']);
        });
    });
});
