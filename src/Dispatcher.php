<?php

namespace uzdevid\websocket;

use uzdevid\websocket\messages\Error;
use Workerman\Connection\TcpConnection;

class Dispatcher {
    private WebSocket $webSocket;

    public function __construct(WebSocket &$webSocket) {
        $this->webSocket = $webSocket;
    }

    public function onConnect(TcpConnection $connection): void { }

    public function onMessage(TcpConnection $connection, $data): void {
        $payload = json_decode($data, true);

        $response = new Response($connection);

        if (!isset($payload['method'], $payload['body'])) {
            $response->message(new Error('Invalid payload structure'))->send();
        }

        $router = new Router($payload['method'], $payload['body'], $response);

        $router->webSocket($this->webSocket);

        $responseMessage = $router->run();
        $response->message($responseMessage)->send();
    }

    public function onClose(TcpConnection $connection) { }
}