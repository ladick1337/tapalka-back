<?php

namespace Database\Seeders;

use App\Consts\Permissions;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Admin::where('login', 'root')->delete();

        $role = Role::create([
           'name' => 'Test role',
           'permissions' => array_keys(Permissions::HINTS)
        ]);

        Admin::factory()
            ->credentials('root', 'root')
            ->for($role)
            ->create();

    }
}
