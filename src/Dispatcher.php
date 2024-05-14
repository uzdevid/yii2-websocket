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
    private array $connectionHeaders = [];

    /**
     * @param TcpConnection $connection
     * @return void
     */
    public function onConnect(TcpConnection $connection): void {
        Yii::$app->addConnection($connection);

        $connection->onWebSocketConnect = function ($connection, $header) {
            $this->connectionHeaders[$connection->id] = http_parse_headers($header);
        };
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

        $messageConfig = Json::decode($payload);

        $messageConfig['headers'] = $this->connectionHeaders[$connection->id];

        $request->message = (new Hydrator())->create(Message::class, $messageConfig);

        $request->url = str_replace(':', '/', $request->message->method);

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