<?php

namespace uzdevid\websocket\handler\base;

use uzdevid\websocket\base\Application;
use uzdevid\websocket\WebSocket;
use Workerman\Connection\TcpConnection;
use yii\base\InvalidRouteException;

class Dispatcher {
    private WebSocket $webSocket;

    public function __construct(WebSocket &$webSocket) {
        $this->webSocket = $webSocket;
    }

    public function onConnect(TcpConnection $connection): void {
        $this->webSocket->clients()->addConnection($connection);
    }

    /**
     * @param TcpConnection $connection
     * @param $data
     *
     * @throws InvalidRouteException
     */
    public function onMessage(TcpConnection $connection, $data): void {
        $response = new Response($connection);

        $payload = json_decode($data, true);

        $path = str_replace('.', '/', $payload['method']);

        $app = new Application();

        $result = $app->runAction($path);

        $response->message($result)->send();
    }

    public function onClose(TcpConnection $connection): void {
        $this->webSocket->clients()->removeConnection($connection->id);
    }
}