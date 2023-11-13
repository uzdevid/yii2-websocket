<?php

namespace uzdevid\websocket;

use Workerman\Connection\TcpConnection;
use yii\base\Arrayable;

class Response {
    private TcpConnection $connection;
    private Arrayable|array $message;

    public function __construct(TcpConnection $connection) {
        $this->connection = $connection;
    }

    public function message(Arrayable|array $message): static {
        $this->message = $message;
        return $this;
    }

    private function encodedMessage(): bool|string {
        return json_encode($this->message, JSON_UNESCAPED_UNICODE);
    }

    public function send(): bool|null {
        $message = $this->encodedMessage();

        return $this->connection->send($message);
    }
}