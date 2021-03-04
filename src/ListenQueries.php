<?php

namespace Supliu\LaravelQueryMonitor;

use Closure;
use Illuminate\Support\Str;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\Socket\ConnectionInterface;

class ListenQueries
{
    /**
     * @var Closure
     */
    private $info;

    /**
     * @var Closure
     */
    private $warn;

    /**
     * @var boolean
     */
    private $debug = false;

    function __construct(string $host, int $port, int $moreThanMiliseconds)
    {
        $this->host = $host;
        $this->port = $port;
        $this->moreThanMiliseconds = $moreThanMiliseconds;
        $this->loop = Factory::create();
        $this->socket = new Server($host.':'.$port, $this->loop);
    }

    public function setInfo(Closure $info)
    {
        $this->info = $info;
    }

    public function setWarn(Closure $warn)
    {
        $this->warn = $warn;
    }

    public function setDebug(bool $debug)
    {
        $this->debug = $debug;
    }

    public function run()
    {
        call_user_func($this->info, 'Listen SQL queries on '.$this->host.':'.$this->port . PHP_EOL . PHP_EOL);

        $this->socket->on('connection', function (ConnectionInterface $connection) {

            $connection->on('data', function ($data) use ($connection) {
                
                if($this->debug)
                    call_user_func($this->warn, '# Debug:' . $data);

                $query = json_decode($data, true);


                if ($query === null) {
                    call_user_func($this->warn, '# Something wrong happened with JSON data received: ');
                    call_user_func($this->info, $data);
                } else {

                    if($query['time'] > $this->moreThanMiliseconds) {

                        call_user_func($this->warn, '# Query received:');

                        $bindings = $query['bindings'] ?? [];

                        $normalizedBindings = array_map(function($i){ 
                            return is_string($i) ? '"'.$i.'"' : $i; 
                        }, $bindings);

                        $sql = Str::replaceArray('?', $normalizedBindings, $query['sql']);

                        call_user_func($this->info, '# SQL: ' . $sql);
                        call_user_func($this->info, '# Miliseconds: ' . $query['time']);
                        call_user_func($this->info, '# Seconds: ' . $query['time'] / 1000);
                        call_user_func($this->info, PHP_EOL);
                    }

                }


                $connection->close();
                
            });
        });

        $this->loop->run();
    }
}
