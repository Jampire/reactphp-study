<?php

require 'vendor/autoload.php';

use React\Promise\Deferred;

$deferred = new Deferred();

$promise = $deferred->promise();
$promise->done(
    static function($data) {
        echo 'Done: ', $data, PHP_EOL;
    },
    static function($data) {
        echo 'Rejected: ', $data, PHP_EOL;
    }
);

$deferred->resolve('Hello World!!');
//$deferred->reject('No results.');
