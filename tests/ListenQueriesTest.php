<?php

use Orchestra\Testbench\TestCase;
use Supliu\LaravelQueryMonitor\ListenQueries;

class ListenQueriesTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [\Supliu\LaravelQueryMonitor\ServiceProvider::class];
    }

    /** 
     * @test 
     */
    public function runCommand()
    {
        
        $listenQueries = new ListenQueries('0.0.0.0', 8081);
        
        $listenQueries->setInfo(function($message){
            fwrite(STDERR, print_r($message . PHP_EOL, TRUE));
        });

        $listenQueries->setWarn(function($message){
            fwrite(STDERR, print_r($message . PHP_EOL, TRUE));
        });

        $listenQueries->setDebug(true);

        $listenQueries->run();
    }
}