<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

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
        // Rate limiting for automation endpoints
        RateLimiter::for('automation', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(60)->by($request->user()->id)
                : Limit::perMinute(10)->by($request->ip());
        });

        // Rate limiting for file uploads
        RateLimiter::for('uploads', function (Request $request) {
            return $request->user()
                ? Limit::perHour(50)->by($request->user()->id)
                : Limit::perHour(5)->by($request->ip());
        });
    }
}
