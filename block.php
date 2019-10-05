<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory;

$loop = Factory::create();

$loop->addPeriodicTimer(1, static function() {
    echo 'Hello', PHP_EOL;
});

$loop->addTimer(1, static function() {
    sleep(5);
});

$loop->run();
