<?php

namespace Supliu\LaravelQueryMonitor;

use Closure;
use React\EventLoop\Factory;
use React\Socket\ConnectionInterface;
use React\Socket\Connector;

class DispatchQueries
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
     * @var Connector
     */
    private $connector;

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->loop = Factory::create();
        $this->connector = new Connector($this->loop);
    }

    public function send($query)
    {
        $this->connector->connect($this->host . ':' . $this->port)->then(function (ConnectionInterface $connection) use ($query) {
    
            $connection->write(json_encode($query));

        });
        
        $this->loop->run();
    }
}