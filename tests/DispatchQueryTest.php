<?php

use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;

class DispatchQueryTest extends TestCase
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
        $app['config']->set('laravel-query-monitor.port', 8081);
    }

    /** 
     * @test 
     */
    public function dispatchQuery()
    {
        DB::statement('CREATE TABLE foo (id Int, name varchar)');

        DB::table('foo')->count();

        DB::table('foo')->insert([
            'id' => 1,
            'name' => 'bar'
        ]);

        DB::table('foo')
            ->where('id', '!=', 4)
            ->first();

        DB::table('foo')->count();
    }
}