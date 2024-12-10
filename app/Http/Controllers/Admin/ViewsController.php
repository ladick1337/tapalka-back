<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Settings;
use App\Modules\Traits\AdminGuard;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ViewsController extends Controller
{
    use AdminGuard;

    public function vue() : View
    {
        return view('admin.vue.index');
    }

}
