<?php

namespace UzDevid\WebSocket\Server\Collection;

use Countable;
use Iterator;
use UzDevid\WebSocket\Server\Dto\User;
use UzDevid\WebSocket\Trait\CountableTrait;
use UzDevid\WebSocket\Trait\IteratorTrait;
use yii\web\NotFoundHttpException;

class Users implements Countable, Iterator {
    use CountableTrait;
    use IteratorTrait;

    private array $container;

    /**
     * @param User $client
     * @return void
     */
    public function add(User $client): void {
        $this->container[$client->id] = $client;
    }

    /**
     * @param string|int $id
     * @return User
     * @throws NotFoundHttpException
     */
    public function get(string|int $id): User {
        if (!isset($this->container[$id])) {
            throw new NotFoundHttpException('Client not found');
        }

        return $this->container[$id];
    }

    /**
     * @param string|int $id
     * @return bool
     */
    public function remove(string|int $id): bool {
        try {
            $client = $this->get($id);
        } catch (NotFoundHttpException $e) {
            return false;
        }

        foreach ($client->getConnections() as $connection) {
            $connection->close();
        }

        $client->close();

        return true;
    }

    /**
     * @return User
     */
    public function current(): User {
        return current($this->container);
    }
}