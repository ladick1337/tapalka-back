<?php

namespace App\Modules\TableGenerator;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TableGeneratorResponse
{

    public $items;
    public $pages;
    public $count;

    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->items = collect($paginator->items());
        $this->pages = $paginator->lastPage();
        $this->count = $paginator->count();
    }

    public function map(\Closure $closure) : self
    {

        $this->items = $this->items->map($closure);

        return $this;

    }

}
