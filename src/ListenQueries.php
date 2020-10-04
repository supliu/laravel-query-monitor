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
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var \React\EventLoop\LoopInterface
     */
    private $loop;

    /**
     * @var \React\Socket\ServerInterface
     */
    private $socket;
    
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

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
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
                if ($this->debug) {
                    call_user_func($this->warn, '# Debug:' . $data);
                }

                $query = json_decode($data, true);

                call_user_func($this->warn, '# Query received:');

                if ($query === null) {
                    call_user_func($this->warn, '# Something wrong happened with JSON data received: ');
                    call_user_func($this->info, $data);
                } else {
                    $bindings = $query['bindings'] ?? [];

                    $normalizedBindings = array_map(function ($i) {
                        return is_string($i) ? '"'.$i.'"' : $i;
                    }, $bindings);

                    $sql = Str::replaceArray('?', $normalizedBindings, $query['sql']);

                    call_user_func($this->info, '# SQL: ' . $sql);
                    call_user_func($this->info, '# Time: ' . $query['time'] / 1000 . ' seconds');
                }

                call_user_func($this->info, PHP_EOL);

                $connection->close();
            });
        });

        $this->loop->run();
    }
}
