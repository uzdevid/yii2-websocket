<?php

namespace UzDevid\WebSocket\Dto;

use Generator;
use Yii;
use yii\base\Arrayable;
use yii\helpers\Json;

class Client {
    private array $connectionIds;

    /**
     * @param string|int $id
     */
    public function __construct(
        public string|int $id
    ) {
    }

    /**
     * @return Generator<Connection>
     */
    public function getConnections(): Generator {
        yield Yii::$app->connections->getMultiple($this->connectionIds);
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
        $encodedPayload = JSON::encode(compact($method, $payload), JSON_UNESCAPED_UNICODE);

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