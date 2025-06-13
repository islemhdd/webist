<?php

namespace App\Providers;

use App\Models\Student;
use App\Models\Officer;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void 
    {
        // Custom model binding for Officer - resolve from User data
        Route::bind('id', function ($value) {
            // Check if this is for a route that expects an Officer
            $routeName = request()->route()->getName();
            if (str_contains($routeName, 'brigade.statistics') || 
                str_contains($routeName, 'principale') || 
                str_contains($routeName, 'weekends') ||
                str_contains($routeName, 'infermerie') ||
                str_contains($routeName, 'cons') ||
                str_contains($routeName, 'parametre') ||
                str_contains($routeName, 'sanctions') ||
                str_contains($routeName, 'report.')) {
                
                $user = User::findOrFail($value);
                $officer = $user->isOfficer();
                
                if (!$officer) {
                    abort(403, 'Access denied: User is not an officer');
                }
                
                return $officer;
            }
            
            // For other routes, return the value as is
            return $value;
        });
    }
}
