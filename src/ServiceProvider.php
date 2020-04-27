<?php

namespace Supliu\LaravelQueryMonitor;

use Exception;
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

        /*
         * Load views
         */
        DB::listen(function($query){

            if(env('LARAVEL_ELOQUENT_MONITOR') != 'off'){
                
                if (false == ($socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
                    throw new Exception("socket_create() failed: reason: " . socket_strerror(socket_last_error()));
                }
    
                if (false == (@socket_connect($socket, '127.0.0.1', 8080))) {
                    throw new Exception("socket_bind() failed: reason: " . socket_strerror(socket_last_error($socket)));
                }
    
                $message = json_encode($query);
    
                socket_write($socket, $message, strlen($message)) or die("Could not send data to server");
            }

        });
    }

    protected function bootPublishes()
    {

    }
}