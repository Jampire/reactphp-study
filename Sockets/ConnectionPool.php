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
        $connection->write('Enter your name: ');
        $this->initEvents($connection);
        $this->setConnectionData($connection, []);
    }

    /**
     * @param ConnectionInterface $connection
     */
    private function initEvents(ConnectionInterface $connection): void
    {
        $connection->on('data', function($data) use ($connection) {
            $connectionData = $this->getConnectionData($connection);

            if (empty($connectionData)) {
                $this->addNewMember($data, $connection);
                return;
            }

            $name = $connectionData['name'];
            $this->sendAll("$name: $data", $connection);
        });

        $connection->on('close', function() use ($connection) {
            $data = $this->getConnectionData($connection);
            $name = $data['name'] ?? '';
            $this->connections->offsetUnset($connection);
            $this->sendAll("User $name leaves the chat" . PHP_EOL, $connection);
        });
    }

    private function setConnectionData(ConnectionInterface $connection, $data): void
    {
        $this->connections->offsetSet($connection, $data);
    }

    private function getConnectionData(ConnectionInterface $connection)
    {
        return $this->connections->offsetGet($connection);
    }

    private function addNewMember($name, ConnectionInterface $connection): void
    {
        $name = str_replace(["\n", "\r"], '', $name);
        $this->setConnectionData($connection, ['name' => $name]);
        $this->sendAll("User $name joins the chat" . PHP_EOL, $connection);
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
