<?php

return [
    'enable' => env('LARAVEL_QUERY_MONITOR', true),
    'host' => env('LARAVEL_QUERY_MONITOR_HOST', '0.0.0.0'),
    'port' => env('LARAVEL_QUERY_MONITOR_PORT', 8081),
    
    // The following options are used to filter the queries that are monitored.
    // Useful to ignore queries from Laravel Pulse, Telescope, etc.
    'ignore_query_match' => [
        '/pulse_entries|pulse_aggregates|pulse_values/i',
        '/telescope_entries|telescope_entries_tags|telescope_monitoring/i',
    ],
];
