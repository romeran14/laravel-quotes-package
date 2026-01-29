<?php
namespace LaravelPackageMaker\QuotesPackage\Tests\Unit;
use LaravelPackageMaker\QuotesPackage\Services\QuoteService;
use LaravelPackageMaker\QuotesPackage\Tests\TestCase;

uses(TestCase::class);

test('Bynary search found the quote by id', function () {
    $service = new QuoteService();
    
    $mockData = [
        ['id' => 1, 'quote' => 'Frase A', 'author' => 'Autor A'],
        ['id' => 5, 'quote' => 'Frase B', 'author' => 'Autor B'],
        ['id' => 10, 'quote' => 'Frase C', 'author' => 'Autor C'],
    ];

    $reflection = new \ReflectionClass(QuoteService::class);
    $method = $reflection->getMethod('binarySearch');
    $method->setAccessible(true);

    $result = $method->invokeArgs($service, [$mockData, 5]);

    expect($result)->not->toBeNull();
    expect($result['id'])->toBe(5);
    expect($result['quote'])->toBe('Frase B');
});

test('Bynary search return null in case of fail', function () {
    $service = new QuoteService();
    $mockData = [['id' => 1, 'quote' => 'A']];

    $reflection = new \ReflectionClass(QuoteService::class);
    $method = $reflection->getMethod('binarySearch');
    $method->setAccessible(true);

    $result = $method->invokeArgs($service, [$mockData, 99]);

    expect($result)->toBeNull();
});