![Laravel API Logs](https://raw.githubusercontent.com/CodeTechAgency/laravel-api-logs/main/art/banner.png)

# Laravel API Requests Logger

A lightweight Laravel package for logging requests made to your API.

[![Latest version](https://img.shields.io/github/release/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/releases)
[![Total downloads](https://img.shields.io/packagist/dt/codetech/laravel-api-logs?style=flat-square)](https://packagist.org/packages/codetech/laravel-api-logs)
[![Tests](https://img.shields.io/github/actions/workflow/status/CodeTechAgency/laravel-api-logs/tests.yml?style=flat-square&label=tests)](https://github.com/CodeTechAgency/laravel-api-logs/actions/workflows/tests.yml)
[![GitHub license](https://img.shields.io/github/license/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/LICENSE)

## Requirements

| Package version | Laravel    | PHP  | Status |
|-----------------|------------|------|--------|
| 3.x (this branch) | 11 / 12 / 13 | ≥ 8.2 | Active |
| 2.x ([`v2`](https://github.com/CodeTechAgency/laravel-api-logs/tree/v2)) | 7 – 10 | ≥ 7.2 | Security fixes |
| 1.x ([`v1`](https://github.com/CodeTechAgency/laravel-api-logs/tree/v1)) | 7 – 10 | ≥ 7.2 | End of life |

Upgrading from an older version? See the [upgrade guide](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/UPGRADE.md).

## Installation

Add the package to your Laravel application using composer:

```
composer require codetech/laravel-api-logs
```

The service provider is registered automatically via package discovery.

### Migrations

Publish the migration file:

```
php artisan vendor:publish --provider="CodeTech\ApiLogs\Providers\ApiLogServiceProvider" --tag=migrations
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

## Configuration

Sensitive values are **redacted by default** before a log is stored: common credential fields (`password`, `token`, `access_token`, …) in the request data, query string and response data, and sensitive request headers (`Authorization`, `Cookie`, `X-Api-Key`, …) are replaced with `[REDACTED]`.

To customize the redaction lists or the replacement string, publish the config file:

```
php artisan vendor:publish --provider="CodeTech\ApiLogs\Providers\ApiLogServiceProvider" --tag=config
```

Then adjust `config/api-logs.php`:

```php
return [
    'redact' => [
        'replacement' => '[REDACTED]',
        'keys' => ['password', 'access_token' /* , ... */],
        'headers' => ['authorization', 'cookie' /* , ... */],
    ],
];
```

`keys` are matched case-insensitively and recursively against the request payload, query string and response data; `headers` are matched against request header names.

## Testing

The test suite is split into `Unit` and `Feature` suites. Run everything with:

```
composer test
```

## Changelog

Every release is documented on the [GitHub releases page](https://github.com/CodeTechAgency/laravel-api-logs/releases).

## Support

If this package helps you, consider [starring the repository](https://github.com/CodeTechAgency/laravel-api-logs) — it helps other developers discover it.

---

## License

**codetech/laravel-api-logs** is open-sourced software licensed under the [MIT license](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/LICENSE).

## About CodeTech

[CodeTech](https://www.codetech.pt) is a web development agency based in Matosinhos, Portugal. Oh, and we LOVE Laravel!
