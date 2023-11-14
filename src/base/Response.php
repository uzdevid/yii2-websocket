<?php

namespace uzdevid\websocket\base;

use Workerman\Connection\TcpConnection;

class Response extends \yii\web\Response {
    public $format = \yii\web\Response::FORMAT_JSON;

    protected TcpConnection|null $connection;

    /**
     * @param TcpConnection $connection
     *
     * @return $this
     */
    public function setConnection(TcpConnection $connection): static {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return void
     */
    public function clear(): void {
        $this->connection = null;
        parent::clear();
    }
}