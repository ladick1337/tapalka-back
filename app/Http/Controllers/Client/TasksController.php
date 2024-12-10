<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskHistory;
use App\Modules\Api\ApiError;
use App\Modules\Api\ApiResponses;
use App\Modules\Prepare\ClientPrepare;
use App\Modules\TableGenerator\TableGenerator;
use App\Modules\Traits\ClientGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{

    use ApiResponses, ClientGuard;

    public function list(Request $request)
    {

        $client = $this->client();

        $tasks = Task::query()
            ->where('is_active', true)
            ->where('lang', $client->lang)
            ->whereDoesntHave('history', function($query) use ($client){
                $query->where('client_id', $client->id);
            })
            ->orderBy('id', 'desc')
            ->get();

        return $tasks;


    }

    public function check(Request $request, Task $task) : bool
    {

        $client = $this->client();

        if(!$task->is_active){
            throw new ApiError('Эту задачу нельзя выполнить');
        }

        if($task->history()->where('client_id', $client->id)->first()){
            throw new ApiError('Вы уже выполнили эту задачу');
        }

        if(!$task->check($client)){
            return false;
        }

        DB::transaction(function() use ($task, $client){

            $task->increment('complete_count');

            TaskHistory::factory()
                ->for($task)
                ->for($client)
                ->create();

            $client->restoreBalance($task->reward);

            if($client->parent_id && $client->parent->ref_percent > 0){

                $parentReward = $task->reward * ($client->parent->ref_percent / 100);
                $parentReward = ceil($parentReward);

                if($parentReward >= 1){
                    $client->parent->restoreBalance($parentReward);
                }

            }

        });

        return true;


    }

}
