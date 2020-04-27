<?php

namespace Supliu\LaravelQueryMonitor\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\Socket\ConnectionInterface;

class MonitorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-eloquent-monitor';

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
        $this->info('Listen SQL queries on *:8080' . PHP_EOL . PHP_EOL);

        $loop = Factory::create();
        $socket = new Server('127.0.0.1:8080', $loop);

        $socket->on('connection', function (ConnectionInterface $connection) {

            $connection->on('data', function ($data) use ($connection) {
                
                $query = json_decode($data, true);

                $sql = Str::replaceArray('?', array_map(function($i){ return is_string($i) ? "'$i'" : $i; }, $query['bindings']),$query['sql']);

                $this->warn('# Query recived:');
                $this->info('# SQL: ' . $sql);
                $this->info('# Time: ' . $query['time'] / 1000 . ' seconds');

                $this->info(PHP_EOL);

                $connection->close();
            });
        });

        $loop->run();
    }
}
