<?php

require 'vendor/autoload.php';

use React\Promise\Deferred;

$deferred = new Deferred();
$deferred->promise()
    ->otherwise(static function($data) {
        echo $data, PHP_EOL;

        throw new Exception('some ' . $data);
    })
    ->otherwise(static function(Exception $e) {
        $message = $e->getMessage();
        echo $message, PHP_EOL;

        throw new Exception(strtoupper($message));
    })
    ->otherwise(static function(Exception $e) {
        echo $e->getMessage(), PHP_EOL;
    });
$deferred->reject('error');
