<?php

namespace UzDevid\WebSocket\Entity;

final class Message {
    public string $method = '';
    public array|null $payload = null;
}