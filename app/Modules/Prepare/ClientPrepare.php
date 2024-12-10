<?php

namespace App\Modules\Prepare;

use App\Models\Client;
use App\Models\Task;

class ClientPrepare
{

    public function task(Task $task) : array
    {
        return $task->only([
            'id',
            'title',
            'description',
            'picture',
            'type',
            'lang',
            'url',
            'reward',
            'timeout',
            'created_at'
        ]);
    }

    public function client(Client $client) : array
    {
        return $client->only([
            'id',
            'chat_id',
            'name',
            'username',
            'energy',
            'energy_charges',
            'energy_level',
            'energy_max',
            'balance',
            'lang',
            'invited_friends',
            'energy_bonus_at',
            'activity_at',
            'created_at',
            'energy_bonus_available',
            'energy_auto_recharge_time'
        ]);
    }

}
