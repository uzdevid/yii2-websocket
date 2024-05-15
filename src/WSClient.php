<?php

namespace UzDevid\websocket;

use UzDevid\WebSocket\Server\WebSocketServer;
use WebSocket\Client as WebSocketClient;
use yii\base\Arrayable;
use yii\helpers\Json;

class WSClient {
    private WebSocketServer $webSocket;
    private WebSocketClient $client;

    /**
     * @param WebSocketServer $webSocket
     */
    public function __construct(WebSocketServer $webSocket) {
        $this->webSocket = $webSocket;
        $this->client = new WebSocketClient("{$this->webSocket->clientProtocol}://{$this->webSocket->clientHost}:{$this->webSocket->port}/{$this->webSocket->url}");
    }

    /**
     * @param Arrayable|array $payload
     * @return void
     */
    public function send(Arrayable|array $payload): void {
        $encodedPayload = Json::encode($payload, JSON_UNESCAPED_UNICODE);
        $this->client->text($encodedPayload);
        $this->client->close();
    }
}