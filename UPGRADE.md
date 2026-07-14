# Upgrade Guide

## Upgrading from 2.0 to 2.1

No changes are required — `composer update` is enough. Be aware of one behavioral change:

### Sensitive fields are now redacted by default

Since 2.1.0, credential-type fields in the request data, query string and response data (`password`, `token`, `access_token`, `secret`, `cvv`, …) and sensitive request headers (`Authorization`, `Cookie`, `X-Api-Key`, …) are stored as `[REDACTED]` instead of their real values. If you inspect stored payloads for debugging, expect to see redacted values for those keys.

To customize which fields are redacted — or to restore the previous verbatim behavior by emptying the lists — publish the config file and edit `config/api-logs.php`:

```
php artisan vendor:publish --provider=CodeTech\\ApiLogs\\Providers\\ApiLogServiceProvider --tag=config
```

Two further notes:

- Rows logged **before** 2.1.0 remain unredacted — if your `api_logs` table may contain credentials, consider purging or cleaning the historical data.
- `response_data` is now decoded associatively before storage; reads through the `ApiLog` model are unchanged (the `json` cast already returned arrays).

## Upgrading from 2.x to 3.0

### New requirements

| | 2.x | 3.x |
|---|---|---|
| PHP | ≥ 7.2 | ≥ 8.2 (≥ 8.3 for Laravel 13) |
| Laravel | 7 – 10 | 11 / 12 / 13 |

Update your `composer.json`:

```json
"codetech/laravel-api-logs": "^3.0"
```

If your application is still on Laravel 10 or older, stay on `^2.0` — the 2.x line (this branch) keeps receiving security fixes such as sensitive-field redaction (2.1.0).

### Middleware registration has moved

Laravel 11 removed `app/Http/Kernel.php`. Register the middleware in `bootstrap/app.php` instead:

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

### Request timing is no longer stored in dynamic properties

The middleware previously wrote `$request->start` and `$request->end` directly on the `Request` object (a dynamic property, deprecated since PHP 8.2). The start time now lives in the request attribute bag:

```php
$request->attributes->get('api_logs.start');
```

If your code read `$request->start` or `$request->end`, update it accordingly (`end` has no replacement — it was only ever the log's write time; use the `duration` column of the log instead).

### Stricter middleware signatures

`handle()` now declares a `Symfony\Component\HttpFoundation\Response` return type and `terminate()` type-hints its `$response` parameter. If you extended `LogApiRequest`, update your overrides to match.

### Users without the `HasApiLogs` trait are skipped

`terminate()` now verifies that the authenticated model actually has an `apiLogs()` method before logging. In 2.x an authenticated model without the trait caused a fatal error; in 3.x the request is silently not logged. Make sure your user model uses the trait if you expect its requests to be logged:

```php
use CodeTech\ApiLogs\Traits\HasApiLogs;

class User extends Authenticatable
{
    use HasApiLogs;
}
```

### `ApiLog` casts moved to the `casts()` method

The `$casts` property was replaced by the Laravel 11+ `casts()` method. If you extended `CodeTech\ApiLogs\Models\ApiLog` and defined your own `$casts`, Laravel merges both, but review the result to be sure it's what you expect.

### Non-JSON responses

`response_data` is now stored as `null` for responses without string content (e.g. streamed or binary file responses) instead of attempting to decode them.

### No database changes

The `api_logs` table schema is unchanged — no new migration is required. (The published migration stub is now an anonymous class, which only affects migrations published after the upgrade.)
