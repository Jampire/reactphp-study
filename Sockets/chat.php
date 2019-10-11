<?php

require 'vendor/autoload.php';
require_once __DIR__ . '/ConnectionPool.php';

use React\EventLoop\Factory as LoopFactory;
use React\Socket\Server;
use React\Socket\ConnectionInterface;

$loop = LoopFactory::create();
$socket = new Server('127.0.0.1:9697', $loop);
$pool = new ConnectionPool();

$socket->on('connection', static function(ConnectionInterface $connection) use ($pool) {
    $pool->add($connection);
});

echo 'Listening on ', $socket->getAddress(), PHP_EOL;

$loop->run();
