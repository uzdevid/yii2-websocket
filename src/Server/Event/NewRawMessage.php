<?php

namespace UzDevid\WebSocket\Server\Event;

use UzDevid\WebSocket\Server\Dto\Client;

class NewRawMessage extends BaseEvent {
    /**
     * @param Client $client
     * @param array $message
     */
    public function __construct(
        Client                $client,
        public readonly array $message
    ) {
        parent::__construct($client);
    }
}