<?php

namespace App\Weather;

use App\Weather\Api\OpenWeatherApiClient;
use App\Weather\Api\WeatherApiClientContract;
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

        $this->app->bind(WeatherApiClientContract::class, function () {
            return new OpenWeatherApiClient(
                config('weather.api.key')
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
