# Upgrade Guide

## Upgrading from 1.x to 2.0

2.x supports the same Laravel (7–10) and PHP (≥ 7.2) versions as 1.x — the upgrade is a small schema and model change. It is strongly recommended: the 2.x line receives security fixes, including sensitive-field redaction (2.1.0), which 1.x lacks.

Update your `composer.json`:

```json
"codetech/laravel-api-logs": "^2.0"
```

### The `user_id` column was replaced by a polymorphic `causer`

The `api_logs` table no longer has a `user_id` foreign key; logs are linked to the model that made the request through a polymorphic relation. Migrate your existing table:

```php
// 1. Drop the FK and add the new (nullable) type column
Schema::table('api_logs', function (Blueprint $table) {
    $table->dropForeign(['user_id']);
    $table->string('causer_type')->nullable()->after('user_id');
});

// 2. Backfill the type with your user model's class name
DB::table('api_logs')->update([
    'causer_type' => \App\Models\User::class,
]);

// 3. Rename the id column and add the morph index
Schema::table('api_logs', function (Blueprint $table) {
    $table->renameColumn('user_id', 'causer_id');
    $table->index(['causer_type', 'causer_id'], 'causer');
});
```

Notes: `causer_type` is created nullable so the backfill can run; you may tighten it to `NOT NULL` afterwards if you wish (on Laravel ≤ 10, column changes require the `doctrine/dbal` package). Fresh installs can skip all of this and simply publish and run the 2.x migration.

### Add the `HasApiLogs` trait to your user model

The logs are now written through the authenticated model's `apiLogs()` relation:

```php
use CodeTech\ApiLogs\Traits\HasApiLogs;

class User extends Authenticatable
{
    use HasApiLogs;
}
```

Reading logs changes from querying `ApiLog::where('user_id', …)` to `$user->apiLogs` / `$apiLog->causer`.

## Upgrading from 2.x to 3.0

3.x requires PHP ≥ 8.2 and Laravel 11–13. See the full guide on the [`v2` branch](https://github.com/CodeTechAgency/laravel-api-logs/blob/v2/UPGRADE.md) or on [`main`](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/UPGRADE.md).
