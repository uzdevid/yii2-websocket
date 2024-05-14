<?php

namespace UzDevid\WebSocket\Entity;

final class Message {
    public array $headers = [];
    public string $method = '';
    public array|null $body = null;
}