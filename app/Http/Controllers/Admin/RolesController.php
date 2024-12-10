<?php

namespace App\Http\Controllers\Admin;

use App\Consts\Permissions;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Modules\Api\ApiError;
use App\Modules\Api\ApiResponses;
use App\Modules\Prepare\AdminPrepare;
use App\Modules\TableGenerator\TableGenerator;
use App\Modules\Traits\AdminGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RolesController extends Controller
{

    use ApiResponses, AdminGuard;

    public function list(Request $request) : array
    {

        $data = $request->validate([
            'id' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255']
        ]);

        $generator = new TableGenerator(
            Role::query()->with('admins')
        );

        $generator->setSortFields(['id', 'name']);

        $generator->setPrepareQuery(function($query) use ($data){

            $id = Arr::get($data, 'id') ?: null;
            $name = Arr::get($data, 'name') ?: null;

            if($id){
                $query = $query->where('id', $id);
            }

            if($name){
                $query = $query->where('name', 'LIKE', '%' . $name . '%');
            }

            return $query;

        });

        return $this->tableGeneratorToJson(
            $generator->build($request)->map(function(Role $role){
                return AdminPrepare::role($role);
            })
        );

    }

    public function get(Request $request, Role $role) : array
    {
        return AdminPrepare::role($role);
    }

    public function create(Request $request) : int
    {

        $data = $request->validate([
           'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
           'permissions' => ['required', 'array', 'min:1'],
           'permissions.*' => ['string', Rule::in(array_keys(Permissions::HINTS))]
        ], [
            'name.max' => 'Название слишком длинное',
            'permissions.required' => 'Укажите несколько прав',
            'name.unique' => 'Роль с таким названием уже существует'
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'permissions' => $data['permissions']
        ]);

        return $role->id;

    }

    public function edit(Request $request, Role $role) : void
    {

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['string', Rule::in(array_keys(Permissions::HINTS))]
        ], [
            'name.max' => 'Название слишком длинное',
            'permissions.required' => 'Укажите несколько прав',
            'name.unique' => 'Роль с таким названием уже существует'
        ]);

        $role->name = $data['name'];
        $role->permissions = $data['permissions'];
        $role->save();

    }

    public function remove(Request $request, Role $role) : void
    {

        if($role->admins()->count() > 0){
            throw new ApiError('Невозможно удалить роль, так как есть активные сотрудники', 422);
        }

        $role->delete();

    }

}
