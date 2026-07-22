![Laravel API Logs](https://raw.githubusercontent.com/CodeTechAgency/laravel-api-logs/main/art/banner.png)

# Laravel API Requests Logger

[![Latest version](https://img.shields.io/github/release/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/releases)
[![Total downloads](https://img.shields.io/packagist/dt/codetech/laravel-api-logs?style=flat-square)](https://packagist.org/packages/codetech/laravel-api-logs)
[![Tests](https://img.shields.io/github/actions/workflow/status/CodeTechAgency/laravel-api-logs/tests.yml?style=flat-square&label=tests)](https://github.com/CodeTechAgency/laravel-api-logs/actions/workflows/tests.yml)
[![GitHub license](https://img.shields.io/github/license/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/LICENSE)

A lightweight Laravel package for logging the requests made to your API. Each
authenticated request is stored with its URL, method, IP, payload, headers, response,
and duration — linked to the user that made it, with sensitive values redacted before
anything hits the database.

## Quick start

```bash
composer require codetech/laravel-api-logs
```

Publish the migrations:

```bash
php artisan vendor:publish --provider="CodeTech\ApiLogs\Providers\ApiLogServiceProvider" --tag=migrations
```

Run the migrations:

```bash
php artisan migrate
```

Append the middleware to the `api` middleware group in your `bootstrap/app.php`:

```php
use CodeTech\ApiLogs\Http\Middleware\LogApiRequest;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    // ...
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', LogApiRequest::class);
    })
    // ...
    ->create();
```

Add the `HasApiLogs` trait to the model that makes the requests to browse its history:

```php
use CodeTech\ApiLogs\Traits\HasApiLogs;

class User extends Authenticatable
{
    use HasApiLogs;
}

$user->apiLogs; // all ApiLog entries for the user
```

## Documentation

To learn all about the package — configuration, guards, redaction — head over to
[the extensive documentation](https://www.codetech.pt/open-source/laravel-api-logs).

Upgrading from an older version? See the [upgrade guide](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/UPGRADE.md).

## Changelog

Every release is documented on the [GitHub releases page](https://github.com/CodeTechAgency/laravel-api-logs/releases).

## Contributing

Contributions are welcome! Please read the [contributing guidelines](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/CONTRIBUTING.md) before opening an issue or pull request.

## Security

If you discover a security vulnerability, please follow the [security policy](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/SECURITY.md) — do not report it publicly.

## Support

If this package helps you, consider [starring the repository](https://github.com/CodeTechAgency/laravel-api-logs) —
it helps other developers discover it.

---

## License

**codetech/laravel-api-logs** is open-sourced software licensed under
the [MIT license](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/LICENSE).

## About CodeTech

[CodeTech](https://www.codetech.pt) is a web development agency based in Matosinhos, Portugal. Oh, and we LOVE Laravel!
