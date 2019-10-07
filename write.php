<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory as LoopFactory;
use React\Stream\ReadableResourceStream;
use React\Stream\WritableResourceStream;

$loop = LoopFactory::create();
$input = new ReadableResourceStream(fopen(__FILE__, 'rb'), $loop, 1);
$output = new WritableResourceStream(fopen('php://stdout', 'wb'), $loop);

$input->on('data', static function($data) use ($output) {
    $output->write($data);
});

$input->on('end', static function() use ($output) {
    $output->end();
});

$loop->run();
