<?php

namespace App\Http\Middleware;

use App\Modules\Api\ApiError;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SleepTest
{

    public function handle(Request $request, Closure $next) : JsonResponse
    {

        time_nanosleep(2, 0);

        return $next($request);

    }

}
