<?php

namespace Smartymoon\Generator;

use Illuminate\Support\ServiceProvider;
use Smartymoon\Generator\Commands\InitCommand;
use Smartymoon\Generator\Commands\RollbackCommand;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Config::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'generator');
        if ($this->app->runningInConsole()) {
            $this->commands([
                InitCommand::class,
                RollbackCommand::class,
            ]);
        }
    }
}
