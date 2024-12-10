<?php

namespace App\Http\Controllers\Admin;

use App\Consts\Languages;
use App\Consts\TasksTypes;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Modules\Api\ApiResponses;
use App\Modules\Prepare\AdminPrepare;
use App\Modules\TableGenerator\TableGenerator;
use App\Modules\TableGenerator\TableGeneratorResponse;
use App\Modules\Traits\AdminGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class TasksController extends Controller
{

    use ApiResponses, AdminGuard;

    public function list(Request $request) : array
    {

        $data = $request->validate([
            'id' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'lang' => ['nullable', 'string', Rule::in(array_keys(Languages::HINTS))],
            'title' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable']
        ]);

        $generator = new TableGenerator(
            Task::query()
        );

        $generator->setSortFields(['id', 'name', 'created_at', 'reward', 'complete_count']);

        $generator->setPrepareQuery(function($query) use ($data){

            $id = Arr::get($data ,'id') ?: null;
            $type = Arr::get($data ,'type') ?: null;
            $lang = Arr::get($data ,'lang') ?: null;
            $title = Arr::get($data ,'title') ?: null;
            $is_active = Arr::get($data ,'is_active');

            if($is_active !== null){
                $query = $query->where('is_active', (int)$is_active);
            }

            if($id){
                $query = $query->where('id', $id);
            }

            if($type){
                $query = $query->where('type', $type);
            }

            if($lang){
                $query = $query->where('lang', $lang);
            }

            if($title){
                $query = $query->where('title', 'like', '%'.$title.'%');
            }

            return $query;

        });

        return $this->tableGeneratorToJson(
            $generator->build($request)->map(function(Task $task){
                return AdminPrepare::task($task);
            })
        );


    }

    public function get(Request $request, Task $task) : array
    {
        return AdminPrepare::task($task);
    }


    public function create(Request $request){

        $data = $request->validate([
            'type' => ['required', 'string', Rule::in(array_keys(TasksTypes::HINTS))],
			'title' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string', 'max:4096'],
			'timeout' => ['nullable', 'integer', 'min:0'],
            'reward' => ['required', 'integer', 'min:1', 'max:999999999'],
            'lang' => ['required', 'string', Rule::in(array_keys(Languages::HINTS))],
            'telegram_channel_id' => ['required_if:type,' . TasksTypes::TELEGRAM, 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'picture' => ['nullable', 'string', 'max:255']
        ]);

        $timeout = Arr::get($data,'timeout') ?: 0;
        $telegram_channel_id = Arr::get($data, 'telegram_channel_id') ?: null;
        $description = Arr::get($data,'description') ?: null;

        if($data['type'] === TasksTypes::TELEGRAM){
            $timeout = 0;
        }else{
            $telegram_channel_id = null;
        }

        $picture = Arr::get($data, 'picture') ?: null;

        $task = Task::create([
            'type' => $data['type'],
            'title' => $data['title'],
            'description' => $description,
            'timeout' => $timeout,
            'reward' => $data['reward'],
            'lang' => $data['lang'],
            'telegram_channel_id' => $telegram_channel_id,
            'url' => $data['url'],
            'picture' => $picture ?: ''
        ]);

        return $task->id;

    }

    public function edit(Request $request, Task $task){

        $data = $request->validate([
            'type' => ['required', 'string', Rule::in(array_keys(TasksTypes::HINTS))],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:4096'],
            'timeout' => ['nullable', 'integer', 'min:0'],
            'reward' => ['required', 'integer', 'min:1', 'max:999999999'],
            'lang' => ['required', 'string', Rule::in(array_keys(Languages::HINTS))],
            'telegram_channel_id' => ['required_if:type,' . TasksTypes::TELEGRAM, 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'picture' => ['nullable', 'string', 'max:255']
        ]);

        $timeout = Arr::get($data,'timeout') ?: 0;
        $telegram_channel_id = Arr::get($data, 'telegram_channel_id') ?: null;

        if($data['type'] === TasksTypes::TELEGRAM){
            $timeout = 0;
        }else{
            $telegram_channel_id = null;
        }

        $picture = Arr::get($data, 'picture') ?: null;

        $task->type = $data['type'];
        $task->title = $data['title'];
        $task->description = $data['description'];
        $task->timeout = $timeout;
        $task->reward = $data['reward'];
        $task->lang = $data['lang'];
        $task->telegram_channel_id = $telegram_channel_id;
        $task->url = $data['url'];
        $task->picture = $picture ?: '';
        $task->save();

        return $task->id;

    }

    public function clone(Request $request, Task $task) : int
    {

        $new = $task->replicate();
        $new->complete_count = 0;
        $new->save();

        return $new->id;

    }

    public function toggleActive(Request $request, Task $task, int $status)
    {
        $task->is_active = $status;
        $task->save();
    }

    public function remove(Request $request, Task $task)
    {
        $task->delete();
    }

}
