<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        view()->share('breadcrumbs', []);

        $photoUrl = config('app.default_profile_photo');
        $photoPath = public_path(ltrim($photoUrl, '/'));

        if (! file_exists($photoPath)) {
            Log::warning("Default profile photo missing at {$photoPath}, using bundled fallback.");
            config(['app.default_profile_photo' => '/assets/images/default-avatar.png']);
        }

        if (! extension_loaded('gd')) {
            Log::error('PHP GD extension is not loaded. Image processing features will be unavailable.');
        }
    }
}
