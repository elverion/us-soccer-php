<?php

use App\Stadium\Http\StadiumController;
use Illuminate\Support\Facades\Route;


Route::middleware(['api'])->prefix('api/v1/')->group(function () {
    // Public routes
    Route::prefix('stadium')->group(function () {
        Route::post('/', [StadiumController::class, 'store']);
        Route::get('/{id}', [StadiumController::class, 'show']);
    });
});
