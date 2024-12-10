<?php

namespace App\Modules\Api;

use App\Modules\TableGenerator\TableGeneratorResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ApiResponses
{

    public function tableGeneratorToJson(TableGeneratorResponse $table) : array
    {
        return [
            'items' => $table->items,
            'count' => $table->count,
            'pages' => $table->pages
        ];
    }

    public function paginateToJson(LengthAwarePaginator $paginator, string $key, callable $prepare) : array
    {

        $items = $paginator->items();
        $items = collect($items);
        $items = $items->map($prepare);

        return [
            $key => $items,
            'pagination' => [
                'current' => $paginator->currentPage(),
                'last' => $paginator->lastPage(),
                'total' => $paginator->total()
            ]
        ];

    }
}
