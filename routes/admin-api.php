<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EmployersController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\NotificationsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\TasksController;
use App\Http\Controllers\Admin\SendingsController;
use Illuminate\Support\Facades\Route;

Route::as('.')->group(function(){

    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:admin')->group(function () {

        /**
         * 2FA Google
         */
        Route::as('tfa.')->prefix('/tfa')->group(function(){

            Route::post('/generate', [ProfileController::class, 'generateTFA'])->name('generate');
            Route::post('/confirm', [ProfileController::class, 'confirmTFA'])->name('confirm');
            Route::post('/remove', [ProfileController::class, 'removeTFA'])->name('remove');

        });

        /**
         * Профиль
         */
        Route::as('profile.')->prefix('/profile')->group(function(){

            Route::post('/me', [ProfileController::class, 'me'])->name('me');
            Route::post('/auth-history', [ProfileController::class, 'authHistory'])->name('auth-history');

            Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');

            Route::any('/logout', [AuthController::class, 'logout'])->name('logout');

        });

        /**
         * Уведомления
         */
        Route::as('notifications.')->prefix('/notifications')->group(function(){

            Route::post('/list', [NotificationsController::class, 'listAll'])->name('list');
            Route::post('/list/unread', [NotificationsController::class, 'listUnread'])->name('list-unread');

            Route::post('/read/{notification}', [NotificationsController::class, 'read'])->name('read');
            Route::post('/read-all', [NotificationsController::class, 'readAll'])->name('read-all');

        });

        /**
         * Роли
         */
        Route::as('roles.')->prefix('/roles')->middleware('permission:' . \App\Consts\Permissions::PERMISSION_ROLES)->group(function(){

            Route::post('/list', [RolesController::class, 'list'])->name('list');
            Route::post('/get/{role}', [RolesController::class, 'get'])->name('get');

            Route::post('/create', [RolesController::class, 'create'])->name('create');
            Route::post('/edit/{role}', [RolesController::class, 'edit'])->name('edit');
            Route::post('/remove/{role}', [RolesController::class, 'remove'])->name('remove');

        });

        /**
         * Задачи
         */
        Route::as('tasks.')->prefix('/tasks')->middleware('permission:' . \App\Consts\Permissions::PERMISSION_TASKS)->group(function(){

            Route::post('/list', [TasksController::class, 'list'])->name('list');
            Route::post('/get/{task}', [TasksController::class, 'get'])->name('get');

            Route::post('/create', [TasksController::class, 'create'])->name('create');
            Route::post('/edit/{task}', [TasksController::class, 'edit'])->name('edit');
            Route::post('/clone/{task}', [TasksController::class, 'clone'])->name('clone');
            Route::post('/toggle-active/{task}/{status}', [TasksController::class, 'toggleActive'])->name('toggle-active');
            Route::post('/remove/{task}', [TasksController::class, 'remove'])->name('remove');

        });

        /**
         * Рассылка
         */
        Route::as('sendings.')->prefix('/sendings')->middleware('permission:' . \App\Consts\Permissions::PERMISSION_SENGINGS)->group(function(){

            Route::post('/list', [SendingsController::class, 'list'])->name('list');

            Route::post('/create', [SendingsController::class, 'create'])->name('create');
            Route::post('/cancel/{sending}', [SendingsController::class, 'cancel'])->name('cancel');

        });

        /**
         * Клиенты
         */
        Route::as('clients.')->prefix('/clients')->middleware('permission:' . \App\Consts\Permissions::PERMISSION_CLIENTS)->group(function(){

            Route::post('/list', [ClientsController::class, 'list'])->name('list');
            Route::post('/get/{client}', [ClientsController::class, 'get'])->name('get');
            Route::post('/edit/{client}', [ClientsController::class, 'edit'])->name('edit');

        });

        /**
         * Роли
         */
        Route::as('employers.')->prefix('/employers')->middleware('permission:' . \App\Consts\Permissions::PERMISSION_EMPLOYERS)->group(function(){

            Route::post('/list', [EmployersController::class, 'list'])->name('list');
            Route::post('/get/{admin}', [EmployersController::class, 'get'])->name('get');
            Route::post('/auth-history/{admin}', [EmployersController::class, 'authHistory'])->name('auth-history');

            Route::post('/create', [EmployersController::class, 'create'])->name('create');
            Route::post('/change-password/{admin}', [EmployersController::class, 'changePassword'])->name('change-password');
            Route::post('/disable-tfa/{admin}', [EmployersController::class, 'disableTFA'])->name('disable-tfa');
            Route::post('/edit/{admin}', [EmployersController::class, 'edit'])->name('edit');
            Route::post('/remove/{admin}', [EmployersController::class, 'remove'])->name('remove');

        });

        /**
         * Доп фильтра
         */
        Route::as('filters.')->prefix('/filters')->group(function(){
            Route::post('/roles', [\App\Http\Controllers\Admin\HelpFiltersController::class, 'roles'])->name('roles');
            Route::post('/permissions', [\App\Http\Controllers\Admin\HelpFiltersController::class, 'permissions'])->name('permissions');
            Route::post('/languages', [\App\Http\Controllers\Admin\HelpFiltersController::class, 'languages'])->name('languages');
            Route::post('/tasks-types', [\App\Http\Controllers\Admin\HelpFiltersController::class, 'taskTypes'])->name('task-types');
        });

        Route::post('/upload-file', [\App\Http\Controllers\Admin\FilesController::class, 'uploadFile'])->name('upload-file');

    });

});

