<?php

namespace UzDevid\WebSocket\Dto;

use Generator;
use Yii;
use yii\base\Arrayable;
use yii\helpers\Json;

class User {
    /**
     * @param string|int $id
     * @param array $connectionIds
     */
    public function __construct(
        public string|int $id,
        private array     $connectionIds,
    ) {
    }

    /**
     * @return Generator<Client>
     */
    public function getConnections(): Generator {
        foreach (Yii::$app->connections->getMultiple($this->connectionIds) as $connection) {
            yield $connection;
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function addConnectionId(int $id): void {
        $this->connectionIds[] = $id;
    }

    /**
     * @param string $method
     * @param Arrayable|array $payload
     * @return array
     */
    public function send(string $method, Arrayable|array $payload): array {
        $encodedPayload = JSON::encode(compact('method', 'payload'), JSON_UNESCAPED_UNICODE);

        $successes = $fails = 0;

        foreach ($this->getConnections() as $connection) {
            if ($connection->tcp->send($encodedPayload)) $successes++;
            else $fails++;
        }

        return [$successes, $fails];
    }

    /**
     * @return void
     */
    public function close(): void {
        foreach ($this->getConnections() as $connection) {
            $connection->tcp->close();
        }
    }
}