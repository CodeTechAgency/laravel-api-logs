<?php

namespace CodeTech\ApiLogs\Providers;

use Illuminate\Support\ServiceProvider;

class ApiLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->setPublishableFiles();
    }

    /**
     * Sets the publishable files.
     */
    private function setPublishableFiles(): void
    {
        $databasePath = sprintf('migrations/%s_create_api_logs_table.php', date('Y_m_d_His'));

        $this->publishes([
            __DIR__.'/../../database/migrations/create_api_logs_table.php.stub' => database_path($databasePath),
        ], 'migrations');
    }
}
