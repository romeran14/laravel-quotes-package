<?php

namespace LaravelPackageMaker\QuotesPackage\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use LaravelPackageMaker\QuotesPackage\QuoteServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            QuoteServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('cache.default', 'array');
        $app['config']->set('quotes.request_limit', 5);
        $app['config']->set('quotes.time_window', 30);
    }
}