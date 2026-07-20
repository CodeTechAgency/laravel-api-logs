---
title: Logging requests
weight: 5
group: Usage
---

Add the `HasApiLogs` trait to the model that makes the requests (typically your `User`
model):

```php
use CodeTech\ApiLogs\Traits\HasApiLogs;

class User extends Authenticatable
{
    use HasApiLogs;
}
```

To start logging requests made to your API, append the middleware to the `api`
middleware group in your `bootstrap/app.php`:

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

Only authenticated requests are logged. Each log stores the URL, HTTP method, client
IP, request data, request headers, response data and the request duration — with
[sensitive values redacted](redaction.md) — and is linked to the authenticated user
through a polymorphic `causer` relation:

```php
$user->apiLogs; // all ApiLog entries for the user
$apiLog->causer; // the model that made the request
```
