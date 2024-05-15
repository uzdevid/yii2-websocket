<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Dto\Client;
use UzDevid\WebSocket\Dto\Connection;
use UzDevid\WebSocket\Entity\Message;
use Workerman\Connection\TcpConnection;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;
use yii\console\Exception;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use Yiisoft\Hydrator\Hydrator;

class Dispatcher {
    /**
     * @param TcpConnection $tcpConnection
     * @return void
     */
    public function onConnect(TcpConnection $tcpConnection): void {
        $tcpConnection->onWebSocketConnect = static function ($tcpConnection) {
            $clientId = Yii::$app->security->generateRandomString(8);

            Yii::$app->clients->add(new Client($clientId, [$tcpConnection->id]));
            Yii::$app->connections->add(new Connection($tcpConnection, Yii::$app->request->queryParams, Yii::$app->request->headers, $clientId));
        };
    }

    /**
     * @param TcpConnection $tcpConnection
     * @param $payload
     * @throws Exception
     * @throws InvalidConfigException
     * @throws InvalidRouteException
     * @throws NotFoundHttpException
     */
    public function onMessage(TcpConnection $tcpConnection, $payload): void {
        /** @var Request $request */
        $request = &Yii::$app->request;

        $connection = Yii::$app->connections->get($tcpConnection->id);
        
        $messageConfig = Json::decode($payload);
        $message = (new Hydrator())->create(Message::class, $messageConfig);

        Yii::$app->runAction($request->url, ['client' => $connection->getClient(), 'connection' => $connection, 'payload' => $message->payload]);

        $request->clear();
    }

    /**
     * @param TcpConnection $connection
     * @return void
     */
    public function onClose(TcpConnection $connection): void {
        Yii::$app->connections->remove($connection->id);
    }
}