# Laravel API Requests Logger

A lightweight Laravel package for logging requests made to your API.

[![Latest version](https://img.shields.io/github/release/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/releases)
[![GitHub license](https://img.shields.io/github/license/CodeTechAgency/laravel-api-logs?style=flat-square)](https://github.com/CodeTechAgency/laravel-api-logs/blob/master/LICENSE)

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
