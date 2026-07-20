---
title: Configuration
weight: 4
group: Getting started
---

The package works out of the box with sensible defaults. To change them, publish the
config file:

```bash
php artisan vendor:publish --provider="CodeTech\ApiLogs\Providers\ApiLogServiceProvider" --tag=config
```

Then adjust `config/api-logs.php`:

```php
return [
    'guard' => null,

    'redact' => [
        'replacement' => '[REDACTED]',
        'keys' => ['password', 'access_token' /* , ... */],
        'headers' => ['authorization', 'cookie' /* , ... */],
    ],
];
```

## Guard

`guard` pins the authentication guard used to resolve the user a logged request is
attributed to (e.g. `'sanctum'`). When `null`, the request's default guard is used —
the one set by the `auth` middleware, or the application's default guard.

## Redaction

The `redact` options control which values are stripped from a log before it is
stored — see [Redaction](redaction.md) for the defaults and how the matching works.
