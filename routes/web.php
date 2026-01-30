<?php

use Illuminate\Support\Facades\Route;
use LaravelPackageMaker\QuotesPackage\Http\Controllers\QuoteController;

Route::prefix('api')->group(function () {
    Route::get('/quotes-package', [QuoteController::class, 'getQuotes']);
    Route::get('/quotes-package/{id}', [QuoteController::class, 'show']);
});

Route::controller(QuoteController::class)->group(function () {
    Route::get('quotes-ui', 'index');
    Route::get('quotes-ui/{any}', 'index')->where('any', '.*');
});
