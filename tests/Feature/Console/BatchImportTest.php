<?php

namespace LaravelPackageMaker\QuotesPackage\Tests\Feature\Console;

use Illuminate\Support\Facades\Http;
use LaravelPackageMaker\QuotesPackage\Tests\TestCase;

uses(TestCase::class);
test('command batch-import import quotes succesfully', function () {
    Http::fake([
        'dummyjson.com*' => Http::response([
            'id' => 1,
            'quote' => 'Test Quote',
            'author' => 'Author'
        ], 200),
    ]);

    $this->artisan('quotes:batch-import', ['count' => 1])
         ->expectsOutputToContain('Searching 1 new quotes...')
         ->expectsOutputToContain('Its Done')
         ->assertExitCode(0);
});