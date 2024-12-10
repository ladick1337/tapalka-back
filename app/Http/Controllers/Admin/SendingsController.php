<?php

namespace App\Http\Controllers\Admin;

use App\Consts\Languages;
use App\Consts\SendingStatuses;
use App\Http\Controllers\Controller;
use App\Jobs\SendingStart;
use App\Models\Sending;
use App\Modules\Api\ApiResponses;
use App\Modules\Prepare\AdminPrepare;
use App\Modules\TableGenerator\TableGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class SendingsController extends Controller
{

    use ApiResponses;

    public function create(Request $request)
    {

        $data = $request->validate([
            'text' => ['required', 'string', 'max:4096'],
            'lang' => ['required', 'string', Rule::in(array_keys(Languages::HINTS))]
        ]);

        $sending = Sending::factory()
            ->text($data['text'])
            ->lang($data['lang'])
            ->updateUsersCount()
            ->create();

        SendingStart::dispatch($sending);

    }

    public function cancel(Request $request, Sending $sending){

        $sending->status = SendingStatuses::CANCELED;
        $sending->save();

    }

    public function list(Request $request) : array
    {

        $data = $request->validate([
            'lang' => ['nullable', 'string', Rule::in(array_keys(Languages::HINTS))]
        ]);

        $generator = new TableGenerator(
            Sending::query()
        );

        $generator->setSortFields(['id', 'created_at']);

        $generator->setPrepareQuery(function($query) use ($data){

            $lang = Arr::get($data, 'lang') ?: null;

            if($lang){
                $query->where('lang', $lang);
            }

            return $query;

        });

        return $this->tableGeneratorToJson(
            $generator->build($request)->map(function(Sending $sending){
                return AdminPrepare::sending($sending);
            })
        );

    }

}
