# Laravel API Requests Logger

A lightweight Laravel package for logging requests made to your API.

[![Latest version](https://img.shields.io/github/release/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/releases)
[![GitHub license](https://img.shields.io/github/license/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/blob/master/LICENSE)

> **⚠️ You are viewing the docs for 1.x, which is end-of-life** and receives no further updates — including security fixes such as the sensitive-field redaction added in 2.1.0 (without it, passwords and tokens are stored verbatim in your database). Upgrading to 2.x is a small change — see the [upgrade guide](UPGRADE.md). The latest version is 3.x (Laravel 11–13) on the [`main`](https://github.com/CodeTechAgency/laravel-api-logs/tree/main) branch.

## Requirements

| Package version | Laravel      | PHP   | Status |
|-----------------|--------------|-------|--------|
| 3.x ([`main`](https://github.com/CodeTechAgency/laravel-api-logs/tree/main)) | 11 / 12 / 13 | ≥ 8.2 | Active |
| 2.x ([`v2`](https://github.com/CodeTechAgency/laravel-api-logs/tree/v2)) | 7 – 10 | ≥ 7.2 | Security fixes |
| 1.x (this branch) | 7 – 10 | ≥ 7.2 | End of life |

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


---


## License

**codetech/laravel-api-logs** is open-sourced software licensed under the [MIT license](https://github.com/CodeTechAgency/laravel-api-logs/blob/master/LICENSE).


## About CodeTech

[CodeTech](https://www.codetech.pt) is a web development agency based in Matosinhos, Portugal. Oh, and we LOVE Laravel!
