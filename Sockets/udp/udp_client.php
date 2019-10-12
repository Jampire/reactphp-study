<?php

require_once __DIR__ . '/UdpChatClient.php';

use React\EventLoop\Factory;

(new UdpChatClient('localhost:1235', Factory::create()))->run();
