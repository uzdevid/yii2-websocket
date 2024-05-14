<?php

namespace UzDevid\WebSocket;

use Workerman\Connection\TcpConnection;

/**
 * @property TcpConnection|null $connection
 */
class Response extends \yii\web\Response {
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
     * @return TcpConnection|null
     */
    public function getConnection(): TcpConnection|null {
        return $this->connection;
    }

    /**
     * @return void
     */
    public function clear(): void {
        $this->connection = null;
        parent::clear();
    }
}