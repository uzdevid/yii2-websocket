<?php

namespace UzDevid\WebSocket\Entity;

final class Message {
    public string $method = '';
    public array $headers = [];
    public array|null $body = null;
}