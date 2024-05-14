<?php

namespace UzDevid\WebSocket\Collection;

use UzDevid\websocket\Entity\Message;
use Workerman\Connection\TcpConnection;
use yii\base\Arrayable;
use yii\helpers\Json;

class Client {
    public string|int $id;
    private array $_connections = [];

    /**
     * @param string|int $id
     */
    public function __construct(string|int $id) {
        $this->id = $id;
    }

    /**
     * @param TcpConnection $connection
     * @return $this
     */
    public function addConnection(TcpConnection $connection): static {
        $this->_connections[$connection->id] = $connection;
        return $this;
    }

    /**
     * @param int $id
     * @return TcpConnection|null
     */
    public function getConnection(int $id): TcpConnection|null {
        return $this->_connections[$id] ?? null;
    }

    /**
     * @return TcpConnection[]
     */
    public function getConnections(): array {
        return $this->_connections;
    }

    /**
     * @param TcpConnection $connection
     * @return $this
     */
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

        foreach ($this->getConnections() as $connection) {
            $connection->send($encodedPayload);
        }
    }
}