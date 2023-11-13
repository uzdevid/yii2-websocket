<?php

namespace uzdevid\websocket\client;

use uzdevid\websocket\handler\base\Message;
use Workerman\Connection\TcpConnection;

class Client {
    public string|int $id;
    private array $connections = [];

    public function __construct(string|int $id) {
        $this->id = $id;
    }

    public function addConnection(TcpConnection $connection): static {
        $this->connections[$connection->id] = $connection;
        return $this;
    }

    public function getConnection(int $id): TcpConnection|null {
        return $this->connections[$id] ?? null;
    }

    public function getConnections(): array {
        return $this->connections;
    }

    public function removeConnection(TcpConnection $connection): static {
        unset($this->connections[$connection->id]);
        return $this;
    }

    public function send(Message $payload): void {
        $encodedPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);
        foreach ($this->connections as $connection) {
            $connection->send($encodedPayload);
        }
    }
}