<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SECURITY: Middleware to add security headers to all responses.
 * These headers help protect against XSS, clickjacking, and other attacks.
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent clickjacking by denying frame embedding
        $response->headers->set('X-Frame-Options', 'DENY');

        // Enable XSS filter in browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy (disable dangerous features)
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Enable HSTS in production (requires HTTPS)
        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
