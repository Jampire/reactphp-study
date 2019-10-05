<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory;

$loop = Factory::create();

$loop->addTimer(1, static function () {
   echo 'After timer', PHP_EOL;
});

echo 'Before timer', PHP_EOL;

$loop->run();
