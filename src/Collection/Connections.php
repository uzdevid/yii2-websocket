<?php

namespace UzDevid\WebSocket\Collection;

use Countable;
use Generator;
use Iterator;
use UzDevid\WebSocket\Dto\Connection;
use UzDevid\WebSocket\Trait\CountableTrait;
use UzDevid\WebSocket\Trait\IteratorTrait;
use yii\web\NotFoundHttpException;

class Connections implements Countable, Iterator {
    use CountableTrait;
    use IteratorTrait;

    /**
     * @param array $container
     */
    public function __construct(
        private array $container = []
    ) {
    }

    /**
     * @param Connection $connection
     * @return void
     */
    public function add(Connection $connection): void {
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
     * @return Connection
     * @throws NotFoundHttpException
     */
    public function get(int $id): Connection {
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
     * @return Connection
     */
    public function current(): Connection {
        return current($this->container);
    }
}