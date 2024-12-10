<?php

namespace App\Http\Controllers\Admin;

use App\Consts\Languages;
use App\Consts\Permissions;
use App\Consts\TasksTypes;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Modules\Api\ApiError;
use App\Modules\Api\ApiResponses;
use App\Modules\TableGenerator\TableGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class HelpFiltersController extends Controller
{

    use ApiResponses;

    public function roles(Request $request) : Collection
    {

        return Role::query()->orderBy('name')->get()->map(function(Role $role){
            return $role->only(['id', 'name']);
        });

    }

    public function permissions(Request $request) : array
    {
        return Permissions::HINTS;
    }

    public function languages() : array
    {
        return Languages::HINTS;
    }

    public function taskTypes() : array
    {
        return TasksTypes::HINTS;
    }

}
