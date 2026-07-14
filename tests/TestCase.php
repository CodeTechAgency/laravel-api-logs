<?php

namespace CodeTech\ApiLogs\Tests;

use CodeTech\ApiLogs\Providers\ApiLogServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ApiLogServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
    }

    private function runMigrations(): void
    {
        $migration = require __DIR__.'/../database/migrations/create_api_logs_table.php.stub';
        $migration->up();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }
}
