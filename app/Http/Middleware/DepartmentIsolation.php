<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RequestModel;

class DepartmentIsolation
{
    /**
     * Middleware to restrict users to their own department's requests
     */
    public function handle(Request $request, Closure $next)
    {
        // If viewing a specific request, check department access
        if ($request->route('request')) {
            $requestModel = RequestModel::findOrFail($request->route('request')->id ?? $request->route('request'));
            $user = auth()->user();

            // If no authenticated user, deny access
            if (!$user) {
                abort(403, 'Unauthorized to view this request.');
            }

            // Allow if user is the requestor or in the same department or is admin
            if ($requestModel->requestor_id !== $user->id 
                && $requestModel->department_id !== $user->department_id 
                && !$user->hasRole('Admin')) {
                abort(403, 'Unauthorized to view this request.');
            }
        }

        return $next($request);
    }
}
