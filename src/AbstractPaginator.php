<?php

namespace ElemenX\ApiPagination;

use Closure;
use Illuminate\Support\Collection;

/**
 * @mixin \Illuminate\Support\Collection
 */
abstract class AbstractPaginator
{
    /**
     * All of the items being paginated.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $items;

    /**
     * The number of items to be shown.
     *
     * @var int
     */
    protected $limit;

    /**
     * The current offset being "viewed".
     *
     * @var int
     */
    protected $offset;

    /**
     * The current offset being "viewed".
     *
     * @var int
     */
    protected $total;

    /**
     * The query string variable used to store the limit.
     *
     * @var string
     */
    protected $limitName = 'limit';

    /**
     * The query string variable used to store the offset.
     *
     * @var string
     */
    protected $offsetName = 'offset';

    /**
     * The current limit resolver callback.
     *
     * @var \Closure
     */
    protected static $currentLimitResolver;

    /**
     * The current offset resolver callback.
     *
     * @var \Closure
     */
    protected static $currentOffsetResolver;

    /**
     * Determine if the given value is a valid limit number.
     *
     * @param  int  $limit
     * @return bool
     */
    protected function isValidLimitNumber($limit)
    {
        return $limit > 0 && filter_var($limit, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Determine if the given value is a valid offset number.
     *
     * @param  int  $offset
     * @return bool
     */
    protected function isValidOffsetNumber($offset)
    {
        return $offset >= 0 && filter_var($offset, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Get the slice of items being paginated.
     *
     * @return array
     */
    public function items()
    {
        return $this->items->all();
    }

    /**
     * Get the number of items shown per page.
     *
     * @return int
     */
    public function limit()
    {
        return $this->limit;
    }

    /**
     * Get the current offset.
     *
     * @return int
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * Get the current total.
     *
     * @return int
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * Get the query string variable used to store the limit.
     *
     * @return string
     */
    public function getLimitName()
    {
        return $this->limitName;
    }

    /**
     * Set the query string variable used to store the limit.
     *
     * @param  string  $name
     * @return $this
     */
    public function setLimitName($name)
    {
        $this->limitName = $name;

        return $this;
    }

    /**
     * Get the query string variable used to store the offset.
     *
     * @return string
     */
    public function getOffsetName()
    {
        return $this->offsetName;
    }

    /**
     * Set the query string variable used to store the offset.
     *
     * @param  string  $name
     * @return $this
     */
    public function setOffsetName($name)
    {
        $this->offsetName = $name;

        return $this;
    }

    /**
     * Resolve the current limit or return the default value.
     *
     * @param  string  $limitName
     * @param  int  $default
     * @return int
     */
    public static function resolveCurrentLimit($limitName = 'limit', $default = 20)
    {
        if (isset(static::$currentLimitResolver)) {
            return call_user_func(static::$currentLimitResolver, $limitName);
        }

        return $default;
    }

    /**
     * Resolve the current offset or return the default value.
     *
     * @param  string  $offsetName
     * @param  int  $default
     * @return int
     */
    public static function resolveCurrentOffset($offsetName = 'offset', $default = 0)
    {
        if (isset(static::$currentOffsetResolver)) {
            return call_user_func(static::$currentOffsetResolver, $offsetName);
        }

        return $default;
    }

    /**
     * Set the current limit resolver callback.
     *
     * @param  \Closure  $resolver
     * @return void
     */
    public static function currentLimitResolver(Closure $resolver)
    {
        static::$currentLimitResolver = $resolver;
    }

    /**
     * Set the current offset resolver callback.
     *
     * @param  \Closure  $resolver
     * @return void
     */
    public static function currentOffsetResolver(Closure $resolver)
    {
        static::$currentOffsetResolver = $resolver;
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->items->getIterator();
    }

    /**
     * Determine if the list of items is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->items->isEmpty();
    }

    /**
     * Determine if the list of items is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return $this->items->isNotEmpty();
    }

    /**
     * Get the number of items for the current page.
     *
     * @return int
     */
    public function count()
    {
        return $this->items->count();
    }

    /**
     * Get the paginator's underlying collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCollection()
    {
        return $this->items;
    }

    /**
     * Set the paginator's underlying collection.
     *
     * @param  \Illuminate\Support\Collection  $collection
     * @return $this
     */
    public function setCollection(Collection $collection)
    {
        $this->items = $collection;

        return $this;
    }

    /**
     * Determine if the given item exists.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->items->has($key);
    }

    /**
     * Get the item at the given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items->get($key);
    }

    /**
     * Set the item at the given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->items->put($key, $value);
    }

    /**
     * Unset the item at the given key.
     *
     * @param  mixed  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->items->forget($key);
    }

    /**
     * Make dynamic calls into the collection.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->getCollection()->$method(...$parameters);
    }
}
