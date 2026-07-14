# Laravel API Requests Logger

A lightweight Laravel package for logging requests made to your API.

[![Latest version](https://img.shields.io/github/release/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/releases)
[![Tests](https://img.shields.io/github/actions/workflow/status/CodeTechAgency/laravel-api-logs/tests.yml?style=flat-square&label=tests)](https://github.com/CodeTechAgency/laravel-api-logs/actions/workflows/tests.yml)
[![GitHub license](https://img.shields.io/github/license/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/LICENSE)

## Requirements

| Package version | Laravel    | PHP  |
|-----------------|------------|------|
| 3.x             | 11 / 12 / 13 | ≥ 8.2 |
| 2.x             | 7 – 10     | ≥ 7.2 |

Upgrading from 2.x? See the [upgrade guide](UPGRADE.md).

## Installation

Add the package to your Laravel application using composer:

```
composer require codetech/laravel-api-logs
```

The service provider is registered automatically via package discovery.

### Migrations

Publish the migration file:

```
php artisan vendor:publish --provider=CodeTech\\ApiLogs\\Providers\\ApiLogServiceProvider --tag=migrations
```

Run the migration:

```
php artisan migrate
```

## Usage

Add the `HasApiLogs` trait to the model that makes the requests (typically your `User` model):

```php
use CodeTech\ApiLogs\Traits\HasApiLogs;

class User extends Authenticatable
{
    use HasApiLogs;
}
```

To start logging requests made to your API, append the middleware to the `api` middleware group in your `bootstrap/app.php`:

```php
use CodeTech\ApiLogs\Http\Middleware\LogApiRequest;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    // ...
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', LogApiRequest::class);
    })
    // ...
    ->create();
```

Only authenticated requests are logged. Each log stores the URL, HTTP method, client IP, request data, request headers, response data and the request duration, and is linked to the authenticated user through a polymorphic `causer` relation:

```php
$user->apiLogs; // all ApiLog entries for the user
$apiLog->causer; // the model that made the request
```

## Testing

```
composer test
```

---

## License

**codetech/laravel-api-logs** is open-sourced software licensed under the [MIT license](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/LICENSE).

## About CodeTech

[CodeTech](https://www.codetech.pt) is a web development agency based in Matosinhos, Portugal. Oh, and we LOVE Laravel!
