<?php

namespace UzDevid\WebSocket\Server\Handler;

use Throwable;
use UzDevid\WebSocket\Server\Dto\Client;
use UzDevid\WebSocket\Server\Dto\User;
use UzDevid\WebSocket\Server\Event\CloseConnection;
use UzDevid\WebSocket\Server\Event\Error;
use UzDevid\WebSocket\Server\Event\NewConnection;
use UzDevid\WebSocket\Server\Event\NewMessage;
use UzDevid\WebSocket\Server\Event\NewRawMessage;
use Workerman\Connection\TcpConnection;
use Workerman\Worker;
use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class Dispatcher {
    /**
     * @param TcpConnection $tcpConnection
     * @return void
     */
    public function onConnect(TcpConnection $tcpConnection): void {
        $tcpConnection->onWebSocketConnect = static function (TcpConnection $tcpConnection) {
            $userId = Yii::$app->security->generateRandomString(8);

            $client = new Client($tcpConnection, Yii::$app->request->queryParams, Yii::$app->request->headers, $userId);
            $user = new User($userId,  [Client::getUid($tcpConnection)]);

            Yii::$app->clients->add($client);
            Yii::$app->users->add($user);

            Yii::$app->trigger(NewConnection::class, new NewConnection($client));
        };
    }

    /**
     * @param TcpConnection $tcpConnection
     * @param $payload
     */
    public function onMessage(TcpConnection $tcpConnection, $payload): void {
        try {
            $client = Yii::$app->clients->get(Client::getUid($tcpConnection));
        } catch (NotFoundHttpException $e) {
            return;
        }

        try {
            $payloadMessage = Json::decode($payload);
        } catch (Throwable) {
            return;
        }

        Yii::$app->trigger(NewRawMessage::class, new NewRawMessage($client, $payloadMessage));

        if (!isset($payloadMessage['method'], $payloadMessage['payload'])) {
            return;
        }

        $method = str_replace(':', '/', $payloadMessage['method']);

        Yii::$app->trigger(NewMessage::class, new NewMessage($client, $payloadMessage['method'], $payloadMessage['payload']));

        $params = [
            'client' => $client,
            'user' => $client->user,
            'payload' => $payloadMessage['payload']
        ];

        try {
            Yii::$app->runAction($method, $params);
        } catch (Throwable $e) {
            Yii::error($e->getMessage());
        }
    }

    /**
     * @param TcpConnection $tcpConnection
     * @return void
     */
    public function onClose(TcpConnection $tcpConnection): void {
        try {
            $client = Yii::$app->clients->get(Client::getUid($tcpConnection));
        } catch (NotFoundHttpException $e) {
            return;
        }

        Yii::$app->clients->remove(Client::getUid($tcpConnection));
        Yii::$app->trigger(CloseConnection::class, new CloseConnection($client));
    }

    /**
     * @param TcpConnection $tcpConnection
     * @param int $code
     * @param string $message
     * @return void
     */
    public function onError(TcpConnection $tcpConnection, int $code, string $message): void {
        try {
            $client = Yii::$app->clients->get(Client::getUid($tcpConnection));
        } catch (NotFoundHttpException $e) {
            return;
        }

        Yii::$app->trigger(Error::class, new Error($client, $code, $message));
    }

    /**
     * @param Worker $worker
     * @return void
     */
    public function onWorkerExit(Worker $worker): void {
        foreach ($worker->connections as $tcpConnection) {
            try {
                $client = Yii::$app->clients->get(Client::getUid($tcpConnection));
            } catch (NotFoundHttpException $e) {
                return;
            }

            Yii::$app->clients->remove(Client::getUid($tcpConnection));
            Yii::$app->trigger(CloseConnection::class, new CloseConnection($client));
        }
    }
}