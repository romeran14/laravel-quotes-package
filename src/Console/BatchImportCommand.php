<?php

namespace LaravelPackageMaker\QuotesPackage\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use LaravelPackageMaker\QuotesPackage\Services\QuoteService;
use LaravelPackageMaker\QuotesPackage\Exceptions\RateLimitExceededException;

class BatchImportCommand extends Command
{
    protected $signature = 'quotes:batch-import {count}';
    protected $description = 'Importunique quotes';

    public function handle(QuoteService $service)
    {
        $count = (int) $this->argument('count');
        $importedCount = 0;
        $window = config('quotes.time_window', 30);
        
        $this->info("Searching {$count} new quotes...");

        while ($importedCount < $count) {
            // 1. Create ramdon ID for quotes
            $randomId = rand(1, 100);

            // 2. Checking if we have it before require it to API
            $cachedQuotes = Cache::get('cached_quotes_list', []);
            
            // Binary Search
            if ($this->isAlreadyCached($cachedQuotes, $randomId)) {
                $this->line("ID {$randomId} is already saved  in cache. Next...");
                continue; // Continue to next iteration of while
            }

            try {
                // 3. If doesn't exits we require it from API
                $service->getQuote($randomId);
                
                $importedCount++;
                $this->info("Imported: {$importedCount}/{$count} (ID: {$randomId})");

            } catch (RateLimitExceededException $e) {
                $this->warn("API Limit Reached. Waiting {$window}s...");
                sleep($window);
            }
        }

        $this->info("Its Done. Added {$count} new  uniques quotes.");
    }

    
    private function isAlreadyCached(array $quotes, int $id): bool
    {
        // check for empty array
        if (count($quotes) === 0) return false;

        $low = 0;
        $high = count($quotes) - 1;

        while ($low <= $high) {
            // compute middle index
            $mid = (int) floor(($low + $high) / 2);
            // element found at mid
            if ($quotes[$mid]['id'] == $id) {
                return true;
            }

            if ($quotes[$mid]['id'] < $id) {
                // search the right side of the array
                $low = $mid + 1;
            } else {
                // search the left side of the array
                $high = $mid - 1;
            }
        }
        // If we reach here element x doesnt exist
        return false;
    }
}