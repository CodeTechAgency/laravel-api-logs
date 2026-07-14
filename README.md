# Laravel API Requests Logger

A lightweight Laravel package for logging requests made to your API.

[![Latest version](https://img.shields.io/github/release/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/releases)
[![GitHub license](https://img.shields.io/github/license/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/blob/v2/LICENSE)

> **You are viewing the docs for the 2.x line** (Laravel 7–10). The latest version is 3.x (Laravel 11–13) on the [`main`](https://github.com/CodeTechAgency/laravel-api-logs/tree/main) branch — see the [upgrade guide](https://github.com/CodeTechAgency/laravel-api-logs/blob/v2/UPGRADE.md).

## Requirements

| Package version | Laravel      | PHP   |
|-----------------|--------------|-------|
| 3.x             | 11 / 12 / 13 | ≥ 8.2 |
| 2.x             | 7 – 10       | ≥ 7.2 |
| 1.x (EOL)       | 7 – 10       | ≥ 7.2 |

## Installation

Add the package to your Laravel application using composer:

```
composer require codetech/laravel-api-logs
```


### Service Provider

The service provider will be automatically registered during the installation process. However, you can manually register it by adding it to the list of providers located in your `config/app.php` file:

```
'providers' => [
    ...
    Codetech\ApiLogs\Providers\ApiLogServiceProvider::class,

],
```


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

To start logging requests made to your API, you simply add the middleware to the API's route middleware group, located in your `app/Http/Kernel.php`:

```
use CodeTech\ApiLogs\Http\Middleware\LogApiRequest;


protected $middlewareGroups = [
    ...

    'api' => [
        ...
        LogApiRequest::class,
    ],
];
```


## Configuration

Sensitive values are **redacted by default** (since 2.1.0) before a log is stored: common credential fields (`password`, `token`, `access_token`, …) in the request data, query string and response data, and sensitive request headers (`Authorization`, `Cookie`, `X-Api-Key`, …) are replaced with `[REDACTED]`.

To customize the redaction lists or the replacement string, publish the config file:

```
php artisan vendor:publish --provider=CodeTech\\ApiLogs\\Providers\\ApiLogServiceProvider --tag=config
```

Then adjust `config/api-logs.php`. `keys` are matched case-insensitively and recursively against the request payload, query string and response data; `headers` are matched against request header names.

## Testing

```
composer test
```

---


## License

**codetech/laravel-api-logs** is open-sourced software licensed under the [MIT license](https://github.com/CodeTechAgency/laravel-api-logs/blob/v2/LICENSE).


## About CodeTech

[CodeTech](https://www.codetech.pt) is a web development agency based in Matosinhos, Portugal. Oh, and we LOVE Laravel!
