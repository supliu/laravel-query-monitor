<?php

namespace Supliu\LaravelQueryMonitor\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\Socket\ConnectionInterface;
use Supliu\LaravelQueryMonitor\ListenQueries;

class MonitorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-query-monitor {--host=} {--port=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show in real-time SQL Queries';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $host = $this->option('host') ?? '0.0.0.0';
        $port = $this->option('port') ?? '8081';

        $listenQueries = new ListenQueries($host, $port);
        
        $listenQueries->setInfo(function($message){
            $this->info($message);
        });

        $listenQueries->setWarn(function($message){
            $this->warn($message);
        });

        $listenQueries->run();
    }
}
