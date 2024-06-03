<?php

namespace App\Stadium;

use App\Stadium\Repositories\{EloquentStadiumRepository, StadiumRepositoryContract};
use Illuminate\Support\ServiceProvider;

class StadiumServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/Routes/api.php');

        $this->app->bind(StadiumRepositoryContract::class, EloquentStadiumRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
