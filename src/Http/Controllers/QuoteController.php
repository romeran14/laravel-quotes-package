<?php

namespace LaravelPackageMaker\QuotesPackage\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use LaravelPackageMaker\QuotesPackage\Services\QuoteService;

class QuoteController extends Controller
{
    public function index()
    {
        // Return blades view
        return view('quotes::index');
    }

    public function getQuotes()
    {
        // Return  quotes  on cache
        return response()->json(Cache::get('cached_quotes_list', []));
    }

    public function show($id, QuoteService $service)
    {
        try {
            $quote = $service->getQuote((int)$id);
            return response()->json($quote);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 429);
        }
    }
}