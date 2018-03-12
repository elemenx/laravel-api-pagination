# Note

Inspired By Illuminate Pagination, This Package Used For Api Pagination (by limit/offset)


# Changelog

 - **0.1.6** Fix Wrong Default Limit Value
 - **0.1.5** Fix Error When No Data
 - **0.1.4** Fix Typo.
 - **0.1.3** Fix Scope.
 - **0.1.2** Bump Version.
 - **0.1.1** Fix Unresolved Dependency.
 - **0.1.0** Init Version.

## How to install (steps)

### 1. Install using Composer

```
composer require "elemenx/laravel-api-pagination"
```

### 2. Required changes in bootstrap/app.php (If using Lumen)

On bootstrap/app.php add:

```
$app->register(ElemenX\ApiPagination\PaginationServiceProvider::class);
```

### 3.Add Trait in Your Models

```
use ElemenX\ApiPagination\Paginatable;

class User {
    use Paginatable;
}
```

## Example

This is an example for how to use this package.

```
$users = User::apiPaginate(100); // 100 is your defalut limit number
```

when you visit with

```
http://api.dev/user?limit=100&offset=0
```

It will return Structure As follows.

```
[
    'data' => [
        [
            'id' => 1,
            'name' => 'test'
        ]
    ],
    'meta' => [
        'limit' => 100,
        'offset' => 0,
        'total' => 1
    ]
]
```
