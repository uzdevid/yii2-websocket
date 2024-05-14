<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Entity\Message;
use Workerman\Connection\TcpConnection;
use Yii;
use yii\base\InvalidRouteException;
use yii\console\Exception;
use yii\helpers\Json;

class Dispatcher {
    /**
     * @param WebSocket $webSocket
     */
    public function __construct(
        private WebSocket $webSocket
    ) {
    }

    public function onConnect(TcpConnection $connection): void {
        $this->webSocket->clients()->addConnection($connection);
    }

    /**
     * @param int $connectionId
     * @param $payload
     * @throws Exception
     * @throws InvalidRouteException
     */
    public function onMessage(int $connectionId, $payload): void {
        /** @var Request $request */
        $request = &Yii::$app->request;

        $payload = Json::decode($payload);

        $request->message = new Message($payload);

        $request->loadHeaders($request->message->headers);

        $request->url = str_replace('.', '/', $request->message->method);

        $request->rawBody = $request->message->body === null ? null : JSON::encode($request->message->body);

        Yii::$app->runAction($request->url);

        $request->clear();
    }

    public function onClose(TcpConnection $connection): void {
        $this->webSocket->clients()->removeConnection($connection->id);
    }
}