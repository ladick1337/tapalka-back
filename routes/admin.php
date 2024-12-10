<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ViewsController;
use App\Consts\Permissions;

//Route::middleware('auth:admin')->group(function () {

    /**
     * Главная
     */
    Route::get('/', [ViewsController::class, 'dashboard']);

    Route::as('.')->group(function(){

        /**
         * Профиль
         */
        Route::get('/profile', [ViewsController::class, 'vue'])->name('profile');

        /**
         * Настройки
         */
        Route::middleware('permission:' . Permissions::PERMISSION_SETTINGS)->group(function(){
            Route::get('/settings', [ViewsController::class, 'vue'])->name('settings');
        });

        /**
         * Роли
         */
        Route::as('roles')->prefix('/roles')->middleware('permission:' . Permissions::PERMISSION_ROLES)->group(function(){

            Route::get('/', [ViewsController::class, 'vue']);

            Route::as('.')->group(function(){
                Route::get('/create', [ViewsController::class, 'vue'])->name('create');
                Route::get('/detail/{role}', [ViewsController::class, 'vue'])->name('detail');
            });

        });

        /**
         * Сотрудники
         */
        Route::as('employers')->prefix('/employers')->middleware('permission:' . Permissions::PERMISSION_EMPLOYERS)->group(function(){

            Route::get('/', [ViewsController::class, 'vue']);

            Route::as('.')->group(function(){
                Route::get('/create', [ViewsController::class, 'vue'])->name('create');
                Route::get('/detail/{admin}', [ViewsController::class, 'vue'])->name('detail');
            });

        });

    });


//});



