<?php

namespace LaravelPackageMaker\QuotesPackage\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use LaravelPackageMaker\QuotesPackage\Exceptions\RateLimitExceededException;

class QuoteService
{
    protected string $baseUrl;
    protected int $requestLimit;
    protected int $timeWindow;

    public function __construct()
    {
        // Load quotes API config
        $this->baseUrl = config('quotes.base_url', 'https://dummyjson.com/quotes');
        $this->requestLimit = config('quotes.request_limit', 5);
        $this->timeWindow = config('quotes.time_window', 30);
    }

    /**
     * @throws RateLimitExceededException
     */
    public function fetch(int $id = null): array
    {
        $this->checkRateLimit();

        // Add id to url if there are anyone
        $url = $id ? "{$this->baseUrl}/{$id}" : $this->baseUrl;

        // Request to api quotes
        $response = Http::withoutVerifying()->get($url);
        
        if ($response->failed()) {
             return [];
        }

        return $response->json();
    }

    /**
     * Rate Limiting
     */
protected function checkRateLimit(): void
    {
        // 1. Identify the user by IP
        $clientIp = request()->ip() ?? '127.0.0.1';
        
        // Create a unique key
        $key = 'quotes_access:' . $clientIp;

        // 2. Verification exceeded the limit
        if (RateLimiter::tooManyAttempts($key, $this->requestLimit)) {
            throw new RateLimitExceededException();
        }

        // 3. Increment the counter for this specific IP
        RateLimiter::hit($key, $this->timeWindow);
    }

    public function getQuote(int $id)
{
    // 1.Obtain
    $quotes = Cache::get('cached_quotes_list', []);

    // 2. Look for quote
    $found = $this->binarySearch($quotes, $id);

    if ($found !== null) {
        return $found;
    }

    // 3. if doenst exists go to API
    $newQuote = $this->fetch($id);

    // 4. put new quote  and sort array
    if (!empty($newQuote) && isset($newQuote['id'])) {
        
        $quotes[] = $newQuote;
        
        usort($quotes, function ($a, $b) {
            return ($a['id'] ?? 0) <=> ($b['id'] ?? 0);
        });

        Cache::forever('cached_quotes_list', $quotes);

        return $newQuote;
    }

    // return empty if fails
    return [];
}



private function binarySearch(array $elements, int $targetId): ?array
{
    $low = 0;
    $high = count($elements) - 1;

    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        $current = $elements[$mid];

        if ($current['id'] == $targetId) {
            return $current; // found
        }

        if ($current['id'] < $targetId) {
            $low = $mid + 1;
        } else {
            $high = $mid - 1;
        }
    }

    return null; // not found
}
}