<?php

namespace UzDevid\WebSocket\Server\Event;

use UzDevid\WebSocket\Server\Dto\Client;

class NewMessage extends BaseEvent {
    /**
     * @param Client $client
     * @param string $method
     * @param array $payload
     */
    public function __construct(
        Client                 $client,
        public readonly string $method,
        public readonly array  $payload
    ) {
        parent::__construct($client);
    }
}