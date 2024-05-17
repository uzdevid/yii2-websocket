<?php

namespace UzDevid\WebSocket\Server\Event;

use UzDevid\WebSocket\Server\Dto\Client;

class Error extends BaseEvent {
    /**
     * @param Client $client
     * @param int $code
     * @param string $message
     */
    public function __construct(
        Client                 $client,
        public readonly int    $code,
        public readonly string $message
    ) {
        parent::__construct($client);
    }
}