<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Entity\Message;
use Workerman\Connection\TcpConnection;
use yii\base\InvalidRouteException;
use yii\helpers\Json;

class Dispatcher {
    private \yii\web\Application $application;

    /**
     * @param WebSocket $webSocket
     */
    public function __construct(
        private WebSocket $webSocket
    ) {
        $this->application = new \yii\web\Application();
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
        $request = &$this->application->request;
        $response = &$this->application->response;

        $response->setConnection($connection);

        $payload = Json::decode($data);

        $request->message = new Message($payload);

        $request->url = str_replace('.', '/', $request->message->method);
        $request->rawBody = $request->message->body === null ? null : JSON::encode($request->message->body);

        $request->loadHeaders($request->message->headers);

        $this->application->runAction($request->url);

        $response->clear();
        $request->clear();
    }

    public function onClose(TcpConnection $connection): void {
        $this->webSocket->clients()->removeConnection($connection->id);
    }
}