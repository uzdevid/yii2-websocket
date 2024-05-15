<?php

namespace UzDevid\WebSocket\Server\Dto;

final class Message {
    public string $method = '';
    public array|null $payload = null;
}