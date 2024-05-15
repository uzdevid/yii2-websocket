<?php

namespace UzDevid\WebSocket\Collection;

use Countable;
use Generator;
use Iterator;
use UzDevid\WebSocket\Dto\Client;
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
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool {
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
     * @param int $id
     * @return Client
     * @throws NotFoundHttpException
     */
    public function get(int $id): Client {
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

        return array_intersect_key($this->container, $ids);
    }

    /**
     * @return Client
     */
    public function current(): Client {
        return current($this->container);
    }
}