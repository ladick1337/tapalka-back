<?php

namespace App\Modules\TableGenerator;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class TableGenerator
{

    protected $query;

    protected $sortFields = ['id'];

    protected $prepareQueryFunction = null;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function setSortFields(array $fields) : self
    {
        $this->sortFields = $fields;
        return $this;
    }

    public function setPrepareQuery(callable $cb) : self
    {
        $this->prepareQueryFunction = $cb;
        return $this;
    }

    public function build(Request $request) : TableGeneratorResponse
    {

        $data = $request->validate([
            'page' => ['required', 'integer', 'min:1', 'max:999999'],
            'limit' => ['required', 'integer', 'min:1', 'max:100'],
            'sortField' => ['required', 'string', Rule::in($this->sortFields)],
            'sortMode' => ['required', 'string', 'in:asc,desc']
        ]);

        $query = $this->query->orderBy($data['sortField'], $data['sortMode']);

        if($this->prepareQueryFunction){
            $query = call_user_func_array($this->prepareQueryFunction, [$query]);
        }

        $response = $query->paginate($data['limit'], ['*'], 'page', $data['page']);

        return new TableGeneratorResponse($response);

    }

}
