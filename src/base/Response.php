<?php

namespace uzdevid\websocket\base;

use Workerman\Connection\TcpConnection;

class Response extends \yii\web\Response {
    public $format = \yii\web\Response::FORMAT_JSON;

    protected TcpConnection $connection;

    public function setConnection(TcpConnection $connection): static {
        $this->connection = $connection;
        return $this;
    }

    protected function sendContent(): void {
        $this->connection->send($this->content);
    }
}