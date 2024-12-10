<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

    private $guard;

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request) : ?string
    {

        if (! $request->expectsJson()) {

            if($this->guard === 'admin'){
                return route('admin.login');
            }else{
                return '/';
            }

        }

        return null;

    }

    public function handle($request, \Closure $next, ...$guards)
    {
        $this->guard = count($guards) ? $guards[0] : null;
        $this->authenticate($request, $guards);

        return $next($request);
    }


}
