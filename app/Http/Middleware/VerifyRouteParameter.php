<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * SECURITY: Middleware to verify that the authenticated user matches the route parameter.
 * This prevents IDOR (Insecure Direct Object Reference) attacks where users try to
 * access other users' data by changing route parameters.
 */
class VerifyRouteParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $parameterName  The name of the route parameter to verify (default: 'id')
     */
    public function handle(Request $request, Closure $next, string $parameterName = 'id'): Response
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $routeParameterValue = $request->route($parameterName);

        // Handle both model binding and raw parameter values
        $parameterId = is_object($routeParameterValue) ? $routeParameterValue->id : $routeParameterValue;

        if ($parameterId != $user->id) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Unauthorized: You can only access your own data.'
                ], 403);
            }
            abort(403, 'Unauthorized: You can only access your own data.');
        }

        return $next($request);
    }
}
