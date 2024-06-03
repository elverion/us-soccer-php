<?php

use App\Stadium\Http\StadiumController;
use Illuminate\Support\Facades\Route;


Route::middleware(['api'])->prefix('api/v1/')->group(function () {
    Route::prefix('stadiums')->group(function () {
        Route::get('/', [StadiumController::class, 'index']);
        Route::post('/', [StadiumController::class, 'store']);
    });
});
