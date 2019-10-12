<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/ChatClient.php';

use React\EventLoop\LoopInterface;
use React\Stream\ReadableStreamInterface;
use React\Stream\ReadableResourceStream;
use React\Datagram\Factory;
use React\Datagram\Socket;


class UdpChatClient implements ChatClient
{
    const TYPE_ENTER = 'enter';
    const TYPE_LEAVE = 'leave';
    const TYPE_MESSAGE = 'message';

    /** @var LoopInterface */
    protected $loop;

    /** @var string */
    protected $address;

    /** @var ReadableStreamInterface */
    protected $stdin;

    /** @var Socket */
    protected $socket;

    /** @var string */
    protected $name = '';

    public function __construct(string $address, LoopInterface $loop)
    {
        $this->address = $address;
        $this->loop = $loop;
    }

    public function run()
    {
        $factory = new Factory($this->loop);
        $this->stdin = new ReadableResourceStream(STDIN, $this->loop);

        $this->stdin->on('data', [$this, 'processInput']);

        $factory->createClient($this->address)
            ->then(
                [$this, 'initClient'],
                static function (Exception $exception) {
                    echo 'Error: ', $exception->getMessage(), PHP_EOL;
                }
            );

        $this->loop->run();
    }

    public function initClient(Socket $client)
    {
        $this->socket = $client;

        $this->socket->on('message', static function($message) {
            echo $message, PHP_EOL;
        });

        $this->socket->on('close', function () {
            $this->loop->stop();
        });

        echo 'Enter your name: ';
    }

    public function processInput(string $data): void
    {
        $data = trim($data);

        if (empty($this->name)) {
            $this->name = $data;
            $this->sendData('', self::TYPE_ENTER);
            return;
        }

        if ($data === ':exit') {
            $this->sendData('', self::TYPE_LEAVE);
            $this->socket->end();
            return;
        }

        $this->sendData($data);
    }

    protected function sendData(string $message, string $type = self::TYPE_MESSAGE): void
    {
        $data = [
            'type' => $type,
            'name' => $this->name,
            'message' => $message,
        ];

        $this->socket->send(json_encode($data));
    }
}
