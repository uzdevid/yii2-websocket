<?php

namespace uzdevid\websocket;

use WebSocket\Client as WebSocketClient;
use yii\base\Arrayable;
use yii\helpers\Json;

class WSClient {
    private WebSocket $webSocket;
    private WebSocketClient $client;

    /**
     * @param WebSocket $webSocket
     */
    public function __construct(WebSocket $webSocket) {
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