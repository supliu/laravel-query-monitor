<?php

use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;
use Supliu\LaravelQueryMonitor\ListenQueries;

class LaravelQueryMonitorTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [\Supliu\LaravelQueryMonitor\ServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('laravel-query-monitor.enable', true);
        $app['config']->set('laravel-query-monitor.host', '0.0.0.0');
        $app['config']->set('laravel-query-monitor.port', 8082);
    }

    /** 
     * @test 
     */
    public function runCommand()
    {
        // TODO
    }
}