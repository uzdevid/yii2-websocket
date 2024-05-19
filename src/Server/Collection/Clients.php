<?php

namespace UzDevid\WebSocket\Server\Collection;

use Countable;
use Generator;
use Iterator;
use UzDevid\WebSocket\Server\Dto\Client;
use UzDevid\WebSocket\Trait\CountableTrait;
use UzDevid\WebSocket\Trait\IteratorTrait;
use yii\web\NotFoundHttpException;

class Clients implements Countable, Iterator {
    use CountableTrait;
    use IteratorTrait;

    private array $container = [];

    /**
     * @param Client $connection
     * @return void
     */
    public function add(Client $connection): void {
        $this->container[$connection->id] = $connection;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function remove(string $id): bool {
        try {
            $connection = $this->get($id);
        } catch (NotFoundHttpException $e) {
            return false;
        }

        $connection->tcp->close();
        unset($this->container[$id]);

        return true;
    }

    /**
     * @param string $id
     * @return Client
     * @throws NotFoundHttpException
     */
    public function get(string $id): Client {
        if (!isset($this->container[$id])) {
            throw new NotFoundHttpException('Connection not found');
        }

        return $this->container[$id];
    }

    /**
     * @param array $ids
     * @return Generator
     */
    public function getMultiple(array $ids): Generator {
        foreach ($ids as $id) {
            try {
                yield $this->get($id);
            } catch (NotFoundHttpException $e) {
                continue;
            }
        }
    }

    /**
     * @return Client
     */
    public function current(): Client {
        return current($this->container);
    }
}