<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class CheckRolePermission
{
    public function handle(Request $request, Closure $next)
    {
        // Get the logged-in user
        $user = Auth::user();

        // Ensure the user has a valid role_id
        if (!$user || !$user->role_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Retrieve the Role model using the role_id stored in the User model
        $role = Role::find($user->role_id);

        // If the role doesn't exist, deny access
        if (!$role) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get the current route name
        $routeName = $request->route()->getName();

        // Check if the role has permission for the given route
        $hasPermission = $role->permissions()
            ->where('name', $routeName)  // Check if the role has permission for this route
            ->exists();

        // If permission doesn't exist, return a Forbidden response
        if (!$hasPermission) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // Allow the request to proceed
        return $next($request);
    }
}
