<?php

namespace ElemenX\ApiPagination;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait Paginatable
{
    public function scopeApiPaginate(Builder $query, $limit = 20, $offset = 0, $columns = ['*'], $limitName = 'limit', $offsetName = 'offset')
    {
        $limit = Paginator::resolveCurrentLimit($limitName) ?: $limit;
        $offset = Paginator::resolveCurrentOffset($offsetName) ?: $offset;

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
