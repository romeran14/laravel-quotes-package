<?php

namespace LaravelPackageMaker\QuotesPackage\Tests\Feature\Http;

use Illuminate\Support\Facades\Cache;
use LaravelPackageMaker\QuotesPackage\Tests\TestCase;

uses(TestCase::class);

test('Endpoint return correct structure', function () {

    Cache::forever('cached_quotes_list', [
        ['id' => 1, 'quote' => 'Hello World', 'author' => 'Kanye West']
    ]);

    $response = $this->getJson('/api/quotes-package');

    $response->assertStatus(200)
             ->assertJsonFragment(['quote' => 'Hello World']);
});