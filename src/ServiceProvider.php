<?php

namespace Supliu\LaravelQueryMonitor;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Supliu\LaravelQueryMonitor\Commands\MonitorCommand;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MonitorCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../config/laravel-query-monitor.php' => config_path('laravel-query-monitor.php'),
        ]);

        /*
         * Setting
         */
        $host = config('laravel-query-monitor.host');
        $port = config('laravel-query-monitor.port');
        $enable = config('laravel-query-monitor.enable');
        $ignore_query_match = config('laravel-query-monitor.ignore_query_match', []);

        if ($host && $port && $enable) {
            $dispatchQueries = new DispatchQueries($host, (int) $port);

            DB::listen(function ($query) use ($dispatchQueries, $ignore_query_match) {
                if (is_array($ignore_query_match) && !empty($ignore_query_match)) {
                    foreach ($ignore_query_match as $ignore) {
                        if (preg_match($ignore, $query->sql)) {
                            return;
                        }
                    }
                }
                $dispatchQueries->send($query);
            });
        }
    }
}
