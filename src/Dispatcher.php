<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Dto\Connection;
use UzDevid\WebSocket\Entity\Message;
use UzDevid\WebSocket\Helper\HeaderParser;
use Workerman\Connection\TcpConnection;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;
use yii\console\Exception;
use yii\helpers\Json;
use Yiisoft\Hydrator\Hydrator;

class Dispatcher {
    private array $connectionHeaders = [];

    /**
     * @param TcpConnection $tcpConnection
     * @return void
     */
    public function onConnect(TcpConnection $tcpConnection): void {
        $tcpConnection->onWebSocketConnect = static function ($tcpConnection, $header) {
            Yii::$app->addConnection(new Connection($tcpConnection, $_GET, HeaderParser::parse($header)));
        };
    }

    /**
     * @param TcpConnection $connection
     * @param $payload
     * @throws Exception
     * @throws InvalidRouteException
     * @throws InvalidConfigException
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