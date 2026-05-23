<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

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
        // Max 5 failed login attempts per minute per IP
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Max 3 failed verify code attempts per minute per IP
        RateLimiter::for('verify-code', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        // Max 3 resend attempts per minute per IP
        RateLimiter::for('resend-code', function (Request $request) {
            return Limit::perMinute(1)->by($request->ip());
        });
    }
}