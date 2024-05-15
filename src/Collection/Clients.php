<?php

namespace UzDevid\WebSocket\Collection;

use Countable;
use Iterator;
use UzDevid\WebSocket\Dto\Client;
use UzDevid\WebSocket\Trait\CountableTrait;
use UzDevid\WebSocket\Trait\IteratorTrait;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * @method Client current()
 */
class Clients implements Countable, Iterator {
    use CountableTrait;
    use IteratorTrait;

    private array $container;

    /**
     * @param Client $client
     * @return void
     */
    public function add(Client $client): void {
        $this->container[$client->id] = $client;
    }

    /**
     * @param string|int $id
     * @return Client
     * @throws NotFoundHttpException
     */
    public function get(string|int $id): Client {
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
}