<?php

namespace App\Http\Middleware;

use App\Modules\Api\ApiError;
use App\Modules\Traits\AdminGuard;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Permission
{

    use AdminGuard;

    public function handle(Request $request, Closure $next, string $permission)
    {

        if($this->admin() && $this->admin()->hasPermission($permission)){
            return $next($request);
        }

        abort(403);

    }



}
