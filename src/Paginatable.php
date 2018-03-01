<?php

namespace ElemenX\ApiPagination;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait Paginatable
{
    public function scopeApiPaginate(Builder $query, $limit = null, $offset = null, $columns = ['*'], $limitName = 'limit', $offsetName = 'offset')
    {
        $limit = $limit ?: Paginator::resolveCurrentLimit($limitName);
        $offset = $offset ?: Paginator::resolveCurrentOffset($offsetName);

        $results = ($total = $query->toBase()->getCountForPagination())
            ? $query->skip($offset)->take($limit)->get($columns)
            : new Collection([]);

        return Container::getInstance()->makeWith(Paginator::class, compact(
            'results',
            'total',
            'limit',
            'offset'
        ));
    }
}
