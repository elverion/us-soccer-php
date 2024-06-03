<?php

namespace App\Weather;

use Illuminate\Support\ServiceProvider;

class WeatherServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // No routes (yet) so disabling for now -- but could forseeably use this in the future.
        // $this->loadRoutesFrom(__DIR__ . '/Http/Routes/api.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
