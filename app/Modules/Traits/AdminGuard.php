<?php
namespace App\Modules\Traits;

use App\Models\Admin;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

trait AdminGuard
{

    protected function admin() : ?Admin
    {
        return $this->adminGuard()->user();
    }

    protected function adminGuard() : Guard
    {
        return Auth::guard('admin');
    }

}
