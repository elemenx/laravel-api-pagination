<?php

namespace ElemenX\ApiPagination;

use Illuminate\Support\ServiceProvider;

class PaginationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Paginator::currentLimitResolver(function ($limitName = 'limit') {
            $limit = $this->app['request']->input($limitName);

            if (filter_var($limit, FILTER_VALIDATE_INT) !== false && (int) $limit >= 1) {
                return (int) $limit;
            }

            return null;
        });

        Paginator::currentOffsetResolver(function ($offsetName = 'offset') {
            $offset = $this->app['request']->input($offsetName);

            if (filter_var($offset, FILTER_VALIDATE_INT) !== false && (int) $offset >= 0) {
                return (int) $offset;
            }

            return 0;
        });
    }
}
