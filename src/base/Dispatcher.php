<?php

namespace uzdevid\websocket\base;

use uzdevid\websocket\entities\Message;
use uzdevid\websocket\entities\ResponseEntity;
use uzdevid\websocket\WebSocket;
use Workerman\Connection\TcpConnection;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;
use yii\helpers\Json;

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
        $request = &$this->webSocket->app->request;
        $response = &$this->webSocket->app->response;

        $response->setConnection($connection);

        $payload = Json::decode($data);

        $request->message = new Message($payload);

        $request->url = str_replace('.', '/', $request->message->method);
        $request->rawBody = $request->message->body === null ? null : JSON::encode($request->message->body);

        $request->loadHeaders($request->message->headers);

        $this->webSocket->app->runAction($request->url);

        $response->clear();
        $request->clear();
    }

    public function onClose(TcpConnection $connection): void {
        $this->webSocket->clients()->removeConnection($connection->id);
    }
}