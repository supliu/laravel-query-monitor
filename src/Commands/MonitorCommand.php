<?php

namespace Supliu\LaravelQueryMonitor\Commands;

use Illuminate\Console\Command;
use Supliu\LaravelQueryMonitor\ListenQueries;

class MonitorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-query-monitor {--host=} {--port=} {--moreThanMiliseconds=} {--debug}';

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
        $debug = $this->option('debug') ?? false;
        $moreThanMiliseconds = $this->option('moreThanMiliseconds') ?? 0;

        $listenQueries = new ListenQueries($host, $port, $moreThanMiliseconds);
        
        $listenQueries->setInfo(function($message){
            $this->info($message);
        });

        $listenQueries->setWarn(function($message){
            $this->warn($message);
        });

        $listenQueries->setDebug($debug);

        $listenQueries->run();
    }
}
