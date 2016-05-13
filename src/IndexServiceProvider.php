<?php

namespace Metrique\Index;

use Illuminate\Support\ServiceProvider;
use Metrique\Index\Commands\IndexMigrationsCommand;
use Metrique\Index\Contracts\IndexRepositoryInterface;
class IndexServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Resources/views/', 'metrique-index');

        // Config
        $this->publishes([
            __DIR__.'/Resources/config/index.php' => config_path('index.php'),
        ], 'index-config');

        // Commands
        $this->commands('command.metrique.migrate-index');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerIndexRepository();
        $this->registerCommands();
    }

    /**
     * Register the IndexRepository singleton binding.
     */
    public function registerIndexRepository()
    {
        $this->app->singleton(
            IndexRepositoryInterface::class,
            function ($app) {
                return $app->make('Metrique\Index\IndexRepositoryCache');
            }
        );
    }

    /**
     * Register the artisan commands.
     */
    private function registerCommands()
    {
        $this->app->singleton('command.metrique.migrate-index', function ($app) {
            return new IndexMigrationsCommand();
        });
    }
}
