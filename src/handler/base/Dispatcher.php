<?php

namespace uzdevid\websocket\handler\base;

use uzdevid\websocket\WebSocket;
use Workerman\Connection\TcpConnection;
use Yii;
use yii\base\InvalidRouteException;
use yii\console\Exception;

class Dispatcher {
    private WebSocket $webSocket;

    public function __construct(WebSocket &$webSocket) {
        $this->webSocket = $webSocket;
    }

    public function onConnect(TcpConnection $connection): void {
        $this->webSocket->clients()->addConnection($connection);
    }

    /**
     * @throws Exception
     * @throws InvalidRouteException
     */
    public function onMessage(TcpConnection $connection, $data): void {
        $response = new Response($connection);

        $payload = json_decode($data, true);

        $path = str_replace('.', '/', $payload['method']);

        $result = Yii::$app->runAction($path);

        $response->message($result)->send();
    }

    public function onClose(TcpConnection $connection): void {
        $this->webSocket->clients()->removeConnection($connection->id);
    }
}