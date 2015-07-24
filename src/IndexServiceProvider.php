<?php

namespace Metrique\Index;

use Illuminate\Support\ServiceProvider;
use Metrique\Index\Commands\IndexMigrationsCommand;
use Metrique\Index\Contracts\IndexRepositoryInterface;
use Metrique\Index\IndexRepositoryCache;
use Metrique\Index\IndexRepositoryEloquent;

class IndexServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        // Config
        $this->publishes([
            __DIR__.'/Resources/config/index.php' => config_path('index.php'),
        ]);
        
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
        $this->registerIndexRepository();
        $this->registerCommands();
    }

    /**
     * Register the IndexRepository singleton bindings.
     *
     * @return void
     */
    public function registerIndexRepository()
    {
        $this->app->singleton(
            IndexRepositoryInterface::class,
            function($app){
                return $app->make('Metrique\Index\IndexRepositoryCache');
            }
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
