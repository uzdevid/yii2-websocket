<?php

namespace uzdevid\websocket;

use WebSocket\Client as WebSocketClient;
use yii\base\Arrayable;

class WSClient {
    private WebSocket $webSocket;
    private WebSocketClient $client;

    public function __construct(WebSocket $webSocket) {
        $this->webSocket = $webSocket;
        $this->client = new WebSocketClient("{$this->webSocket->clientProtocol}://{$this->webSocket->clientHost}:{$this->webSocket->port}/{$this->webSocket->url}");
    }

    public function send(Arrayable|array $payload): void {
        $encodedPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $this->client->text($encodedPayload);
        $this->client->close();
    }
}