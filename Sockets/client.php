<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory as LoopFactory;
use React\Socket\Connector;
use React\Stream\ReadableResourceStream;
use React\Stream\WritableResourceStream;
use React\Socket\ConnectionInterface;

$loop = LoopFactory::create();
$connector = new Connector($loop);
$stdin = new ReadableResourceStream(STDIN, $loop);
$stdout = new WritableResourceStream(STDOUT, $loop);

$connector->connect('127.0.0.1:9697')
    ->then(
        static function(ConnectionInterface $connection) use ($stdin, $stdout) {
            echo 'Connection established', PHP_EOL;
            $stdin->pipe($connection)->pipe($stdout);
        },
        static function(Exception $e) use ($loop) {
            echo 'Cannot connect to server: ', $e->getMessage(), PHP_EOL;
            $loop->stop();
        }
    );

$loop->run();
