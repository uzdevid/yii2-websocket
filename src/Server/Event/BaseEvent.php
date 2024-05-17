<?php

namespace UzDevid\WebSocket\Server\Event;

use UzDevid\WebSocket\Server\Dto\Client;
use yii\base\Event;

class BaseEvent extends Event {
    /**
     * @param Client $client
     */
    public function __construct(
        public readonly Client $client
    ) {
        parent::__construct();
    }
}