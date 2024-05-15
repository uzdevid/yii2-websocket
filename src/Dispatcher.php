<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Dto\Client;
use UzDevid\WebSocket\Dto\Connection;
use Workerman\Connection\TcpConnection;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;
use yii\console\Exception;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

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
        $payloadMessage = Json::decode($payload);

        if (!isset($payloadMessage['method'], $payloadMessage['payload'])) {
            return;
        }

        $connection = Yii::$app->connections->get($tcpConnection->id);

        Yii::$app->runAction(str_replace(':', '/', $payloadMessage['method']), [
            'client' => $connection->getClient(),
            'connection' => $connection,
            'payload' => $payloadMessage['payload']
        ]);
    }

    /**
     * @param TcpConnection $connection
     * @return void
     */
    public function onClose(TcpConnection $connection): void {
        Yii::$app->connections->remove($connection->id);
    }
}