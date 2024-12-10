<?php

namespace App\Modules\Prepare;

use App\Models\Admin;
use App\Models\AdminAuthHistory;
use App\Models\Client;
use App\Models\Role;
use App\Models\Sending;
use App\Models\Task;

class AdminPrepare
{

    static public function sending(Sending $sending) : array
    {
        return $sending->only([
            'id',
            'text',
            'users_complete',
            'users_all',
            'status',
            'created_at',
            'lang'
        ]);
    }

    static public function task(Task $task) : array
    {
        return $task->only([
            'id',
            'title',
            'description',
            'picture',
            'type',
            'lang',
            'url',
            'is_active',
            'reward',
            'timeout',
            'telegram_channel_id',
            'complete_count',
            'created_at'
        ]);
    }

    static public function authHistory(AdminAuthHistory $row) : array
    {
        return $row->only([
            'id',
            'ip',
            'created_at',
            'admin_id'
        ]);
    }

    static public function role(Role $role) : array
    {
        return $role->only([
            'id',
            'name',
            'permissions',
            'created_at'
        ]);
    }

    static public function admin(Admin $admin) : array
    {

        $collection = $admin->only([
            'id',
            'login',
            'role_id',
            'tfa_enabled',
            'created_at',
        ]);

        if($admin->relationLoaded('role')){
            $collection['role'] = self::role($admin->role);
        }

        return $collection;

    }

    static public function client(Client $client) : array
    {

        return $client->only([
            'id',
            'chat_id',
            'ref_percent',
            'name',
            'username',
            'energy',
            'energy_charges',
            'energy_level',
            'energy_max',
            'balance',
            'parent_id',
            'lang',
            'invited_friends',
            'energy_bonus_at',
            'activity_at',
            'created_at',
            'is_alive'
        ]);

    }

}
