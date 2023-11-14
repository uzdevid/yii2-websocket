<?php

namespace uzdevid\websocket\handler\base;

use uzdevid\websocket\base\ApplicationInterface;
use uzdevid\websocket\WebSocket;
use Workerman\Connection\TcpConnection;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;

class Dispatcher {
    private WebSocket $webSocket;

    /**
     * @throws InvalidConfigException
     */
    public function __construct(WebSocket &$webSocket) {
        $this->webSocket = $webSocket;

        if (is_array($this->webSocket->app)) {
            /** @var ApplicationInterface $app */
            $app = Yii::createObject($this->webSocket->app);
            $this->webSocket->app = $app;
        }
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

        $this->webSocket->app->response->set

        $payload = json_decode($data, true);

        $path = str_replace('.', '/', $payload['method']);

        $result = $this->webSocket->app->runAction($path);

        $response->message($result)->send();
    }

    public function onClose(TcpConnection $connection): void {
        $this->webSocket->clients()->removeConnection($connection->id);
    }
}