<?php declare(strict_types=1);

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
    protected $signature = 'laravel-query-monitor {--host=} {--port=} {--debug}';

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
        $host = $this->option('host') ?? config('laravel-query-monitor.host', '0.0.0.0');
        $port = $this->option('port') ?? config('laravel-query-monitor.port', 8081);
        $debug = (bool) $this->option('debug') ?? false;

        (new ListenQueries($host, (int) $port))
            ->setInfo(function ($message) {
                $this->info($message);
            })
            ->setWarn(function ($message) {
                $this->warn($message);
            })
            ->setDebug($debug)
            ->run();
    }
}
