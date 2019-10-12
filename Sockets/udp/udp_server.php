<?php

require_once __DIR__ . '/UdpChatServer.php';

use React\EventLoop\Factory;

(new UdpChatServer('localhost:1235', Factory::create()))->run();
