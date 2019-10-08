<?php

require 'vendor/autoload.php';

use React\Promise\Deferred;

$deferred = new Deferred();
$deferred->promise()
    ->then(static function($data) {
        echo $data, PHP_EOL;
        return $data . ' World!!';
    })
    ->then(static function($data) {
        echo $data, PHP_EOL;
        return strtoupper($data);
    })
    ->then(static function($data) {
        echo $data;
    });
$deferred->resolve('hello');
