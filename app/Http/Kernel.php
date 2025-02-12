<?php

namespace App\Http;

use App\Http\Middleware\ApiThrowable;
use App\Http\Middleware\SleepTest;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [

        'admin-api' => [
//            SleepTest::class,
            \App\Http\Middleware\ApiThrowable::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class
        ],

        'client-api' => [
//            SleepTest::class,
            \App\Http\Middleware\ApiThrowable::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class
        ],

        'services' => [
//            SleepTest::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class
        ]

    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'permission' => \App\Http\Middleware\Permission::class,
    ];
}
