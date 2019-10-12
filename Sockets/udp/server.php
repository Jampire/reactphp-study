<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory as LoopFactory;
use React\Datagram\Factory as DatagramFactory;
use React\Datagram\Socket;

$loop = LoopFactory::create();
$datagramFactory = new DatagramFactory($loop);
$address = 'localhost:1234';

$datagramFactory->createServer($address)
    ->then(
        static function(Socket $server) {
            $server->on('message', static function($message, string $address, Socket $server) {
                $server->send($address . ' echo: ' . $message, $address);
                echo "client $address: $message", PHP_EOL;
            });
        },
        static function(Exception $exception) {
            echo 'Error: ', $exception->getMessage(), PHP_EOL;
        }
    );

echo 'Listening on ', $address, PHP_EOL;
$loop->run();
