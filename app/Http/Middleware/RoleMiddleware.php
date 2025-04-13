<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // 1. Check Authentication FIRST
        if (!Auth::check()) {
            Log::info(
                "RoleMiddleware: Unauthenticated",
                ['url' => $request->fullUrl(), 'token_present' => $request->bearerToken() ? 'Yes' : 'No']
            );
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $user = Auth::user();

        // Optional: Check if role relationship exists before accessing slug
        if (!$user->role) {
            Log::error("RoleMiddleware: User has no role assigned.", [
                'user_id' => $user->id,
            ]);
            return response()->json([
                'message' => 'Forbidden: User role not configured.'
            ], 403);
        }

        Log::info("RoleMiddleware: Checking role", [
            'user_id' => $user->id,
            'user_role_id' => $user->role_id,
            'user_role_slug' => $user->role->slug,
            'expected_role' => $role,
        ]);

        // 2. Check Role
        if ($user->role->slug !== $role) {
            Log::warning("RoleMiddleware: Role mismatch.", [
                'user_id' => $user->id,
                'user_role_slug' => $user->role->slug,
                'expected_role' => $role,
            ]);
            return response()->json([
                'message' => 'Forbidden: Insufficient role permissions.'
            ], 403);
        }

        // 3. If all checks pass, proceed with the request
        // Call $next() only ONCE here
        return $next($request);
    }
}
