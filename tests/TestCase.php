<?php

namespace CodeTech\ApiLogs\Tests;

use CodeTech\ApiLogs\Http\Middleware\LogApiRequest;
use CodeTech\ApiLogs\Providers\ApiLogServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
        $this->defineTestRoutes();
    }

    protected function getPackageProviders($app)
    {
        return [
            ApiLogServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    private function runMigrations()
    {
        require_once __DIR__.'/../database/migrations/create_api_logs_table.php.stub';

        (new \CreateApiLogsTable())->up();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    private function defineTestRoutes()
    {
        Route::middleware(LogApiRequest::class)->group(function () {
            Route::get('/api/ping', function () {
                return response()->json(['pong' => true]);
            });

            Route::post('/api/echo', function () {
                return response()->json(['ok' => true]);
            });

            Route::post('/api/login', function () {
                return response()->json([
                    'user' => ['email' => 'user@example.com'],
                    'access_token' => 'issued-token',
                ]);
            });
        });
    }
}
