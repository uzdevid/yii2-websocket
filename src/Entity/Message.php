<?php

namespace UzDevid\WebSocket\Entity;

use UzDevid\property\loader\Entity;

class Message extends Entity {
    public string $method = '';
    public array $headers = [];
    public array|null $body = null;
}