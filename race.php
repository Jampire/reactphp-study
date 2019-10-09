<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory as LoopFactory;
use React\Promise\Deferred;

$firstResolver = new Deferred();
$secondResolver = new Deferred();
$pending = [
    $firstResolver->promise(),
    $secondResolver->promise(),
];

$promise = \React\Promise\race($pending)
    ->then(static function($resolved) {
        echo 'Resolved with: ', $resolved, PHP_EOL;
    }, static function ($reason) {
        echo 'Failed with: ', $reason, PHP_EOL;
    });

$loop = LoopFactory::create();

$loop->addTimer(2, static function() use ($firstResolver) {
    $firstResolver->resolve(10);
});
$loop->addTimer(1, static function() use ($secondResolver) {
    $secondResolver->resolve(20);
});

$loop->run();
