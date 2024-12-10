<?php

use App\Http\Controllers\Client\GameController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\TasksController;
use App\Http\Controllers\Client\MarketController;
use Illuminate\Support\Facades\Route;

Route::as('.')->group(function(){

    Route::post('/auth/login', [ProfileController::class, 'login']);

    Route::middleware('auth:client')->group(function () {

        Route::post('/profile/me', [ProfileController::class, 'me']);
        Route::post('/profile/set-language', [ProfileController::class, 'setLanguage']);

        Route::post('/game/tap', [GameController::class, 'tap']);
        Route::post('/energy/recharge', [GameController::class, 'energyRecharge']);
        Route::post('/energy/bonus-recharge', [GameController::class, 'energyBonusRecharge']);
        Route::post('/energy/level-up', [GameController::class, 'energyLevelUp']);

        Route::post('/tasks/list', [TasksController::class, 'list']);
        Route::post('/tasks/check/{task}', [TasksController::class, 'check']);

        Route::post('/market/invoice', [MarketController::class, 'invoice']);


    });

});

