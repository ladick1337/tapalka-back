<?php

use Illuminate\Support\Facades\Route;

Route::any('/webhook', [\App\Http\Controllers\ServiceController::class, 'webhook']);
Route::get('/sw', [\App\Http\Controllers\ServiceController::class, 'sw']);
