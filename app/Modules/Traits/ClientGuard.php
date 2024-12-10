<?php
namespace App\Modules\Traits;

use App\Models\Admin;
use App\Models\Client;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

trait ClientGuard
{

    protected function client() : ?Client
    {
        return $this->clientGuard()->user();
    }

    protected function clientGuard() : Guard
    {
        return Auth::guard('client');
    }

}
