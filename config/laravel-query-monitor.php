<?php

return [
    'enable' => env('LARAVEL_QUERY_MONITOR', true),
    'host' => env('LARAVEL_QUERY_MONITOR_HOST', '0.0.0.0'),
    'port' => env('LARAVEL_QUERY_MONITOR_PORT', 8081),
];
