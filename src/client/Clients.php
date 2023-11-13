<?php

namespace uzdevid\websocket\client;

use Workerman\Connection\TcpConnection;

class Clients {
    private array $clients = [];
    private array $connections = [];

    public function add(Client $client): static {
        $this->clients[$client->id] = $client;
        return $this;
    }

    public function get(string|int $id): Client|null {
        return $this->clients[$id] ?? null;
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
}