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

        if ($host && $port && $enable) {
            $dispatchQueries = new DispatchQueries($host, (int) $port);

            DB::listen(function ($query) use ($dispatchQueries) {
                $dispatchQueries->send($query);
            });
        }
    }
}
