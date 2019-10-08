<?php

require 'vendor/autoload.php';

use React\Promise\Deferred;
use React\Promise\PromiseInterface;

/**
 * @param string $url
 * @param string $method
 *
 * @return PromiseInterface
 * @author Dzianis Den Kotau <kotau@us.ibm.com>
 */
function http(string $url, string $method): PromiseInterface
{
    $response = 'Data';
    $deferred = new Deferred();

    if ($response) {
        $deferred->resolve($response);
    } else {
        $deferred->reject(new Exception('No results.'));
    }

    return $deferred->promise();
}

http('http://google.com', 'GET')
    ->then(static function($response) {
        //throw new Exception('error');
        return strtoupper($response);
    })
    ->then(static function($response) {
        echo $response, PHP_EOL;
    })
    ->otherwise(static function(Exception $e) {
        echo $e->getMessage(), PHP_EOL;
    });
