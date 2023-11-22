<?php

namespace uzdevid\websocket\client;

use uzdevid\websocket\entities\Message;
use Workerman\Connection\TcpConnection;
use yii\base\Arrayable;
use yii\helpers\Json;

/**
 * @property TcpConnection[] $connections
 */
class Client {
    public string|int $id;
    private array $_connections = [];

    public function __construct(string|int $id) {
        $this->id = $id;
    }

    public function addConnection(TcpConnection $connection): static {
        $this->_connections[$connection->id] = $connection;
        return $this;
    }

    public function getConnection(int $id): TcpConnection|null {
        return $this->_connections[$id] ?? null;
    }

    /**
     * @return TcpConnection[]
     */
    public function getConnections(): array {
        return $this->_connections;
    }

    public function removeConnection(TcpConnection $connection): static {
        unset($this->_connections[$connection->id]);
        return $this;
    }

    /**
     * @param string $method
     * @param Arrayable|array|null $payload
     * @param array $headers
     *
     * @return void
     */
    public function send(string $method, Arrayable|array|null $payload, array $headers = []): void {
        $encodedPayload = JSON::encode(new Message(compact($method, $payload, $headers)), JSON_UNESCAPED_UNICODE);

        foreach ($this->connections as $connection) {
            $connection->send($encodedPayload);
        }
    }
}