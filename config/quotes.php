<?php

return [
    'base_url' => 'https://dummyjson.com/quotes',
    'request_limit' => 5,
    'time_window' => 30, // seconds
    'default_ip' => env('DEFAULT_IP', '127.0.0.1'),
];