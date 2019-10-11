<?php

use React\Socket\ConnectionInterface;

/**
 * Class ConnectionPool
 */
class ConnectionPool
{
    /** @var SplObjectStorage */
    private $connections;

    public function __construct()
    {
        $this->connections = new SplObjectStorage();
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function add(ConnectionInterface $connection): void
    {
        $connection->write('Hi'. PHP_EOL);

        $this->initEvents($connection);
        $this->connections->attach($connection);

        $this->sendAll('New user enters the chat' . PHP_EOL, $connection);
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function initEvents(ConnectionInterface $connection): void
    {
        $connection->on('data', function($data) use ($connection) {
            $this->sendAll($data, $connection);
        });

        $connection->on('close', function() use ($connection) {
            $this->connections->detach($connection);
            $this->sendAll('A user leaves the chat' . PHP_EOL, $connection);
        });
    }

    /**
     * Send data to all connections from the pool except
     * the specified one.
     *
     * @param mixed $data
     * @param ConnectionInterface $except
     */
    private function sendAll($data, ConnectionInterface $except): void
    {
        /** @var SplObjectStorage $conn */
        foreach ($this->connections as $conn) {
            if ($conn != $except) {
                $conn->write($data);
            }
        }
    }
}
