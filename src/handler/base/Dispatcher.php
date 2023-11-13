<?php

namespace uzdevid\websocket\handler\base;

use uzdevid\websocket\handler\messages\Error;
use uzdevid\websocket\WebSocket;
use Workerman\Connection\TcpConnection;

class Dispatcher {
    private WebSocket $webSocket;

    public function __construct(WebSocket &$webSocket) {
        $this->webSocket = $webSocket;
    }

    public function onConnect(TcpConnection $connection): void {
        $this->webSocket->clients()->addConnection($connection);
    }

    public function onMessage(TcpConnection $connection, $data): void {
        $response = new Response($connection);

        $payload = json_decode($data, true);

        if (!isset($payload['method'], $payload['body'])) {
            $response->message(new Error('Invalid payload structure'))->send();
        }

        $request = new Request($payload['method'], $payload['body'], $payload['headers'] ?? []);

        $router = new Router($request, $response);

        $router->webSocket($this->webSocket);

        $responseMessage = $router->run();
        $response->message($responseMessage)->send();
    }

    public function onClose(TcpConnection $connection): void {
        $this->webSocket->clients()->removeConnection($connection->id);
    }
}