<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Entity\Message;
use Workerman\Connection\TcpConnection;
use Yii;
use yii\base\InvalidRouteException;
use yii\console\Exception;
use yii\helpers\Json;
use Yiisoft\Hydrator\Hydrator;

class Dispatcher {
    /**
     * @param WebSocket $webSocket
     */
    public function __construct(
        private WebSocket $webSocket
    ) {
    }

    /**
     * @param TcpConnection $connection
     * @return void
     */
    public function onConnect(TcpConnection $connection): void {
        Yii::$app->addConnection($connection);
    }

    /**
     * @param TcpConnection $connection
     * @param $payload
     * @throws Exception
     * @throws InvalidRouteException
     */
    public function onMessage(TcpConnection $connection, $payload): void {
        /** @var Request $request */
        $request = &Yii::$app->request;

        $payload = Json::decode($payload);

        $request->message = (new Hydrator())->create(Message::class, $payload);

        $request->loadHeaders($request->message->headers);

        $request->url = str_replace('.', '/', $request->message->method);

        $request->rawBody = $request->message->body === null ? null : JSON::encode($request->message->body);

        Yii::$app->runAction($request->url);

        $request->clear();
    }

    /**
     * @param TcpConnection $connection
     * @return void
     */
    public function onClose(TcpConnection $connection): void {
        Yii::$app->removeConnection($connection->id);
    }
}