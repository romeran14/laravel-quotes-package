<?php

namespace LaravelPackageMaker\QuotesPackage\Tests\Feature;

use Illuminate\Support\Facades\Http;
use LaravelPackageMaker\QuotesPackage\Tests\TestCase;

uses(TestCase::class);

test('system respect request limits simulatin limit reached', function () {

    $responses = [];
    for ($i = 0; $i < 5; $i++) {
        $responses[] = Http::response(['id' => $i+1, 'quote' => 'OK'], 200);
    }
    $responses[] = Http::response(['error' => 'Too many requests'], 429);

    Http::fake([
        'dummyjson.com*' => Http::sequence($responses),
    ]);

    // Command print "API Limit Reached" when gets RateLimitExceededException
    $this->artisan('quotes:batch-import', ['count' => 6])
         ->expectsOutputToContain('API Limit Reached');
});