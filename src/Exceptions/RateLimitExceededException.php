<?php

namespace LaravelPackageMaker\QuotesPackage\Exceptions;

use Exception;

class RateLimitExceededException extends Exception
{
    protected $message = 'API rate limit exceeded. Please wait.';
}