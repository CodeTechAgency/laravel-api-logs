---
title: Installation
weight: 3
group: Getting started
---

Add the package to your Laravel application using Composer:

```bash
composer require codetech/laravel-api-logs
```

The service provider is registered automatically via package discovery.

Publish and run the migrations:

```bash
php artisan vendor:publish --provider="CodeTech\ApiLogs\Providers\ApiLogServiceProvider" --tag=migrations
php artisan migrate
```

Optionally, publish the configuration file:

```bash
php artisan vendor:publish --provider="CodeTech\ApiLogs\Providers\ApiLogServiceProvider" --tag=config
```

See [Configuration](configuration.md) for the available options.
