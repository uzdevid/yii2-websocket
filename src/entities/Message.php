<?php

namespace uzdevid\websocket\entities;

use uzdevid\property\loader\Entity;

class Message extends Entity {
    public string $method = '';
    public array $headers = [];
    public array|null $body = null;

    public static function name(): string {
        return 'Message';
    }
}