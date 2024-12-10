<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminAuthHistory;
use App\Modules\Api\ApiError;
use App\Modules\Api\ApiResponses;
use App\Modules\Prepare\AdminPrepare;
use App\Modules\TableGenerator\TableGenerator;
use Database\Factories\AdminFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class EmployersController extends Controller
{

    use ApiResponses;

    public function list(Request $request) : array
    {

        $data = $request->validate([
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
            'id' => ['nullable', 'string', 'max:255'],
            'login' => ['nullable', 'string', 'max:255']
        ]);

        $generator = new TableGenerator(
            Admin::query()->with('role')
        );

        $generator->setSortFields(['id', 'login', 'role_id', 'created_at']);

        $generator->setPrepareQuery(function($query) use ($data){

            $role = Arr::get($data, 'role_id') ?: null;
            $id = Arr::get($data, 'id') ?: null;
            $login = Arr::get($data, 'login') ?: null;

            if($role){
                $query = $query->where('role_id', $role);
            }

            if($id){
                $query = $query->where('id', $id);
            }

            if($login){
                $query = $query->where('login', 'LIKE', '%' . $login . '%');
            }

            return $query;

        });

        return $this->tableGeneratorToJson(
            $generator->build($request)->map(function(Admin $admin){
                return AdminPrepare::admin($admin);
            })
        );

    }

    public function authHistory(Request $request, Admin $admin) : array
    {

        $generator = new TableGenerator(
            $admin->authHistory()
        );

        $generator->setSortFields(['id', 'created_at']);

        return $this->tableGeneratorToJson(
            $generator->build($request)->map(function(AdminAuthHistory $row){
                return AdminPrepare::authHistory($row);
            })
        );
    }

    public function get(Request $request, Admin $admin) : array
    {

        return AdminPrepare::admin(
            $admin->load('role')
        );
    }

    public function changePassword(Request $request, Admin $admin) : void
    {

        $data = $request->validate([
            'password' => ['required', 'string', 'min:4', 'max:255']
        ], [
            'password.min' => 'Пароль слишком короткий',
            'password.max' => 'Пароль слишком длинный'
        ]);

        if(Hash::check($data['password'], $admin->password)){
            throw new ApiError('Сейчас установлен такой же пароль', 422);
        }

        DB::transaction(function () use ($admin, $data){

            $admin->password = Hash::make($data['password']);
            $admin->save();

            $admin->tokens()->delete();

        });

    }

    public function create(Request $request) : int
    {

        $data = $request->validate([
            'login' => ['required', 'string', 'max:255', 'unique:admins,login', 'regex:/^[A-Za-z_\-0-9]+$/'],
            'password' => ['required', 'string', 'min:4', 'max:255'],
            'role_id' => ['required', 'integer', 'exists:roles,id']
        ], [
            'login.max' => 'Логин слишком длинный',
            'login.unique' => 'Такой логин уже существует',
            'login.regex' => 'Недопустимые символы в логине',
            'password.min' => 'Пароль слишком короткий',
            'password.max' => 'Пароль слишком длинный',
            'role_id.exists' => 'Неизвестная роль'
        ]);

        $admin = Admin::factory()
            ->credentials($data['login'], $data['password'])
            ->role($data['role_id'])
            ->create();

        return $admin->id;

    }

    public function edit(Request $request, Admin $admin) : array
    {

        $data = $request->validate([
           'role_id' => ['required', 'integer', 'exists:roles,id']
        ], [
            'role_id.exists' => 'Неизвестная роль'
        ]);

        $admin->role_id = $data['role_id'];
        $admin->save();

        return AdminPrepare::admin(
            $admin->load('role')
        );

    }

    public function disableTFA(Request $request, Admin $admin) : void
    {

        if(!$admin->tfa_secret){
            throw new ApiError('У пользователя не настроена 2FA');
        }

        $admin->tfa_secret = null;
        $admin->save();

    }

    public function remove(Request $request, Admin $admin) : void
    {

        $admin->delete();

    }

}
