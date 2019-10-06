<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory as LoopFactory;
use React\Stream\ReadableResourceStream;

$loop = LoopFactory::create();
$stream = new ReadableResourceStream(fopen('composer.json', 'r'), $loop, 1);
$stream->on('data', static function($data) use ($stream, $loop) {
    echo $data, PHP_EOL;
    $stream->pause();

    $loop->addTimer(1, static function() use ($stream) {
        $stream->resume();
    });

});

$loop->run();
