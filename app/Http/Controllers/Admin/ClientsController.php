<?php

namespace App\Http\Controllers\Admin;

use App\Consts\Languages;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Modules\Api\ApiResponses;
use App\Modules\Prepare\AdminPrepare;
use App\Modules\TableGenerator\TableGenerator;
use App\Modules\TableGenerator\TableGeneratorResponse;
use App\Modules\Traits\AdminGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ClientsController extends Controller
{

    use ApiResponses, AdminGuard;

    public function list(Request $request) : array
    {

        $data = $request->validate([
            'id' => ['nullable', 'string', 'max:255'],
            'chat_id' => ['nullable', 'string', 'max:255'],
            'lang' => ['nullable', 'string', Rule::in(array_keys(Languages::HINTS))],
            'username' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'is_alive' => ['nullable']
        ]);

        $generator = new TableGenerator(
            Client::query()
        );

        $generator->setSortFields(['id', 'username', 'created_at', 'chat_id', 'balance', 'energy', 'energy_charges', 'energy_max', 'energy_level', 'invited_friends']);

        $generator->setPrepareQuery(function($query) use ($data){

            $id = Arr::get($data, 'id') ?: null;
            $chat_id = Arr::get($data, 'chat_id') ?: null;
            $lang = Arr::get($data, 'lang') ?: null;
            $username = Arr::get($data, 'username') ?: null;
            $name = Arr::get($data, 'name') ?: null;
            $is_alive = Arr::get($data, 'is_alive');

            if($id){
                $query = $query->where('id', $id);
            }

            if($chat_id){
                $query = $query->where('chat_id', 'LIKE', '%' . $chat_id . '%');
            }

            if($lang){
                $query = $query->where('lang', $lang);
            }

            if($username){
                $query = $query->where('username', 'LIKE', '%' . $username . '%');
            }

            if($name){
                $query = $query->where('name', 'LIKE', '%' . $name . '%');
            }

            if($is_alive !== null){
                $query = $query->where('is_alive', $is_alive);
            }

            return $query;

        });

        return $this->tableGeneratorToJson(
            $generator->build($request)->map(function(Client $client){
                return AdminPrepare::client($client);
            })
        );

    }

    public function get(Request $request, Client $client) : array
    {
        return AdminPrepare::client($client);
    }

    public function edit(Request $request, Client $client) : array
    {

        $data = $request->validate([
            'balance' => ['required', 'integer', 'min:0', 'max:9999999999'],
            'ref_percent' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $client->balance = $data['balance'];
        $client->ref_percent = $data['ref_percent'];
        $client->save();

        return AdminPrepare::client($client);

    }

}
