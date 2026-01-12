<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * SECURITY: Only exclude public routes that don't modify state.
     * All authenticated routes should require CSRF tokens.
     */
    protected $except = [
        'login',  // Public login endpoint only
    ];
}
