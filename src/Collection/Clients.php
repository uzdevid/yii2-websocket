<?php

namespace UzDevid\WebSocket\Collection;

use Workerman\Connection\TcpConnection;

class Clients {
    private array $clients = [];
    private array $connections = [];

    /**
     * @param Client $client
     * @return $this
     */
    public function add(Client $client): static {
        $this->clients[$client->id] = $client;
        $this->connections = array_merge($this->connections, $client->getConnections());

        return $this;
    }

    /**
     * @param string|int $id
     * @return Client|null
     */
    public function get(string|int $id): Client|null {
        return $this->clients[$id] ?? null;
    }

    /**
     * @param string|int $id
     * @return $this
     */
    public function remove(string|int $id): static {
        if (!isset($this->clients[$id])) {
            return $this;
        }

        /** @var Client $client */
        $client = $this->clients[$id];

        foreach ($client->getConnections() as $connection) {
            $connection->close();
            $this->removeConnection($connection->id);
        }

        unset($this->clients[$id]);
        return $this;
    }

    /**
     * @return array
     */
    public function all(): array {
        return $this->clients;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function removeConnection(int $id): static {
        unset($this->connections[$id]);
        return $this;
    }

    /**
     * @param int $id
     * @return TcpConnection|null
     */
    public function getConnection(int $id): TcpConnection|null {
        return $this->connections[$id] ?? null;
    }

    /**
     * @return array
     */
    public function getConnections(): array {
        return $this->connections;
    }
}