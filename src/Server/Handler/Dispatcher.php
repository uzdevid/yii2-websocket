<?php

namespace UzDevid\WebSocket\Server\Handler;

use UzDevid\WebSocket\Server\Dto\Client;
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
            Yii::$app->clients->add(new Client($tcpConnection, Yii::$app->request->queryParams, Yii::$app->request->headers));
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

        $connection = Yii::$app->clients->get($tcpConnection->id);

        Yii::$app->runAction(str_replace(':', '/', $payloadMessage['method']), [
            'connection' => $connection,
            'payload' => $payloadMessage['payload']
        ]);
    }

    /**
     * @param TcpConnection $connection
     * @return void
     */
    public function onClose(TcpConnection $connection): void {
        Yii::$app->clients->remove($connection->id);
    }
}