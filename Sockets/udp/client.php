<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory as LoopFactory;
use React\Datagram\Factory as DatagramFactory;
use React\Datagram\Socket;
use React\Stream\ReadableResourceStream;

$loop = LoopFactory::create();
$datagramFactory = new DatagramFactory($loop);
$stdin = new ReadableResourceStream(STDIN, $loop);
$address = 'localhost:1234';

$datagramFactory->createClient($address)
    ->then(
        static function(Socket $client) use ($stdin) {
            $stdin->on('data', static function($data) use ($client) {
                $client->send(trim($data));
            });
            $client->on('message', static function($message) {
                echo $message, PHP_EOL;
            });
        },
        static function(Exception $exception) {
            echo 'Error: ', $exception->getMessage(), PHP_EOL;
        }
    );

$loop->run();
