<?php

namespace LaravelPackageMaker\QuotesPackage;

use Illuminate\Support\ServiceProvider;

class QuoteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/quotes.php', 'quotes'
        );
    }


    public function boot(): void
    {

        $this->publishes([
            __DIR__.'/../config/quotes.php' => config_path('quotes.php'),
        ], 'quotes-config');
        

        if ($this->app->runningInConsole()) {
            
            $this->commands([
                Console\BatchImportCommand::class,
            ]);

        }

        //Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

    }
}