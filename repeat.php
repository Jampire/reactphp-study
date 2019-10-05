<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory;
use React\EventLoop\TimerInterface;

$loop = Factory::create();
$counter = 0;

$loop->addPeriodicTimer(1, static function(TimerInterface $timer) use (&$counter, $loop) {
    $counter++;

    if ($counter === 5) {
        $loop->cancelTimer($timer);
    }

    echo 'Hello', PHP_EOL;
});

$loop->run();
