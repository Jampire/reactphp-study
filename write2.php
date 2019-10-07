<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory as LoopFactory;
use React\Stream\ReadableResourceStream;
use React\Stream\WritableResourceStream;

$loop = LoopFactory::create();
$input = new ReadableResourceStream(fopen('composer.json', 'rb'), $loop, 1);
$output = new WritableResourceStream(fopen('php://stdout', 'wb'), $loop);

$input->pipe($output);

$loop->run();
