<?php

namespace App\Stadium;

use Illuminate\Support\ServiceProvider;

class StadiumServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes/api.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
