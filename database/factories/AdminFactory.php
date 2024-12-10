<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminFactory extends Factory
{

    protected $model = Admin::class;

    public function definition() : array
    {
        return [];
    }

    public function credentials(string $login, string $password) : self
    {
        return $this->state([
            'login' => $login,
            'password' => Hash::make($password)
        ]);
    }

    public function role(int $role_id) : self
    {
        return $this->state(['role_id' => $role_id]);
    }


}
