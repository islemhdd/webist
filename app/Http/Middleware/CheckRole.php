<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // The user model (Officer) should have a 'role' relationship
        $user = Auth::user();

        // Check if user is authenticated and has a role.
        // Then check if the user's role name is in the list of allowed roles.
        if (!$user || !$user->role || !in_array($user->role->name, $roles)) {
            // If not, return a JSON response with a 403 Forbidden error.
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        return $next($request);
    }
}
