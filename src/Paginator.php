<?php

namespace ElemenX\ApiPagination;

use Countable;
use ArrayAccess;
use JsonSerializable;
use IteratorAggregate;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

class Paginator extends AbstractPaginator implements Arrayable, ArrayAccess, Countable, IteratorAggregate, JsonSerializable, Jsonable
{
    /**
     * Create a new paginator instance.
     *
     * @param  mixed     $results
     * @param  int|0     $total
     * @param  int|null  $currentLimit
     * @param  int|null  $currentOffset
     * @param  array     $options (offsetName, limitName)
     * @return void
     */
    public function __construct($results, $total = 0, $limit = null, $offset = null, array $options = [])
    {
        foreach ($options as $key => $value) {
            $this->{$key} = $value;
        }

        $this->total = $total;
        $this->limit = $this->setCurrentLimit($limit);
        $this->offset = $this->setCurrentOffset($offset);

        $this->setItems($results);
    }

    /**
     * Get the current limit for the request.
     *
     * @param  int  $currentLimit
     * @return int
     */
    protected function setCurrentLimit($currentLimit)
    {
        $currentLimit = static::resolveCurrentLimit() ?: $currentLimit;

        return $this->isValidLimitNumber($currentLimit) ? (int)$currentLimit : 10;
    }

    /**
     * Get the current page for the request.
     *
     * @param  int  $currentPage
     * @return int
     */
    protected function setCurrentOffset($currentOffset)
    {
        $currentOffset = $currentOffset ?: static::resolveCurrentOffset();

        return $this->isValidOffsetNumber($currentOffset) ? (int)$currentOffset : 0;
    }

    /**
     * Set the items for the paginator.
     *
     * @param  mixed  $items
     * @return void
     */
    protected function setItems($items)
    {
        $this->items = $items instanceof Collection ? $items : Collection::make($items);

        $this->items = $this->items->slice(0, $this->limit);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'data' => $this->items->toArray(),
            'meta' => [
                'limit' => $this->limit(),
                'offset' => $this->offset(),
                'count' => $this->count(),
                'total' => $this->total()
            ]
        ];
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }
}
