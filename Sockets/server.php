<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory as LoopFactory;
use React\Socket\Server;
use React\Socket\ConnectionInterface;

$loop = LoopFactory::create();
$socket = new Server('127.0.0.1:9696', $loop);

$socket->on('connection', static function(ConnectionInterface $connection) {
    $connection->write('Hi!');
    $connection->on('data', static function($data) use ($connection) {
        $connection->write(strtoupper($data));
    });
});

echo 'Listening on ', $socket->getAddress(), PHP_EOL;

$loop->run();
