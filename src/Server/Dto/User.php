<?php

namespace UzDevid\WebSocket\Server\Dto;

use Generator;
use Yii;
use yii\base\Arrayable;
use yii\helpers\Json;

class User {
    /**
     * @param string|int $id
     * @param array $clientIds
     */
    public function __construct(
        public string|int $id,
        private array $clientIds,
    ) {
    }

    /**
     * @return Generator<Client>
     */
    public function getClients(): Generator {
        foreach (Yii::$app->clients->getMultiple($this->clientIds) as $client) {
            yield $client;
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function addClientId(int $id): void {
        $this->clientIds[] = $id;
    }

    /**
     * @param string $method
     * @param Arrayable|array $payload
     * @return array
     */
    public function send(string $method, Arrayable|array $payload): array {
        $encodedPayload = JSON::encode(compact('method', 'payload'), JSON_UNESCAPED_UNICODE);

        $successes = $fails = 0;

        foreach ($this->getClients() as $connection) {
            if ($connection->tcp->send($encodedPayload)) $successes++;
            else $fails++;
        }

        return [$successes, $fails];
    }

    /**
     * @return void
     */
    public function close(): void {
        foreach ($this->getClients() as $connection) {
            $connection->tcp->close();
        }
    }
}