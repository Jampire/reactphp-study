<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/ChatServer.php';

use React\Datagram\Socket;
use React\EventLoop\LoopInterface;
use React\Datagram\Factory;

class UdpChatServer implements ChatServer
{

    /** @var string */
    protected $address;

    /** @var LoopInterface */
    protected $loop;

    /** @var array */
    protected $clients = [];

    /** @var Socket */
    protected $socket;

    public function __construct(string $address, LoopInterface $loop)
    {
        $this->address = $address;
        $this->loop = $loop;
    }

    public function run(): void
    {
        $datagramFactory = new Factory($this->loop);
        $datagramFactory->createServer($this->address)
            ->then(
                function (Socket $server) {
                    $this->socket = $server;
                    $this->socket->on('message', [$this, 'process']);
                },
                static function(Exception $exception) {
                    echo 'Error: ', $exception->getMessage(), PHP_EOL;
                }
            );

        echo 'Listening on ', $this->address, PHP_EOL;
        $this->loop->run();
    }

    public function process(string $data, string $address): void
    {
        $data = json_decode($data, true);

        switch ($data['type']) {
            case 'enter':
                $this->addClient($data['name'], $address);
                break;
            case 'leave':
                $this->removeClient($address);
                break;
            default:
                $this->sendMessage($data['message'], $address);
        }
    }

    protected function addClient(string $name, string $address): void
    {
        if (array_key_exists($address, $this->clients)) {
            return;
        }

        $this->clients[$address] = $name;
        $this->broadcast("$name enters the chat.", $address);
    }

    protected function removeClient(string $address): void
    {
        $name = $this->clients[$address] ?? '';
        unset($this->clients[$address]);
        $this->broadcast("$name leaves the chat.");
    }

    protected function broadcast(string $message, ?string $except = null): void
    {
        foreach ($this->clients as $address => $name) {
            if ($address === $except) {
                continue;
            }

            $this->socket->send($message, $address);
        }
    }

    protected function sendMessage(string $message, string $address): void
    {
        $name = $this->clients[$address] ?? '';
        $this->broadcast("$name: $message", $address);
    }
}
