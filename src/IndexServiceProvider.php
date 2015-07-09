<?php

namespace Metrique\Index;

use Illuminate\Support\ServiceProvider;
use Metrique\Index\Commands\IndexMigrationsCommand;
use Metrique\Index\Contracts\IndexRepositoryInterface;
use Metrique\Index\EloquentIndexRepository;

class IndexServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Commands
        $this->commands('command.metrique.index-migrations');

        // Views
        $this->loadViewsFrom(__DIR__.'/Resources/views/', 'metrique-index');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerIndex();
        $this->registerCommands();
    }

    /**
     * Register the Index singleton bindings.
     *
     * @return void
     */
    public function registerIndex()
    {
        $this->app->singleton(
            IndexRepositoryInterface::class,
            EloquentIndexRepository::class
        );
    }

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->app->bindShared('command.metrique.index-migrations', function ($app) {
            return new IndexMigrationsCommand();
        });
    }
}