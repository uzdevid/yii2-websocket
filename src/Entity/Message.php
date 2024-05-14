<?php

namespace uzdevid\WebSocket\Entity;

use uzdevid\property\loader\Entity;

class Message extends Entity {
    public string $method = '';
    public array $headers = [];
    public array|null $body = null;
}