<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory as LoopFactory;
use React\Stream\ReadableResourceStream;
use React\Stream\WritableResourceStream;
use React\Stream\ThroughStream;

$loop = LoopFactory::create();
$input = new ReadableResourceStream(fopen('composer.json', 'r+b'), $loop, 1);
$output = new WritableResourceStream(fopen('php://stdout', 'wb'), $loop);
$through = new ThroughStream('strtoupper');

$input->pipe($through)->pipe($output);

$loop->run();
