<?php

namespace UzDevid\WebSocket\Dto;

final class Message {
    public string $method = '';
    public array|null $payload = null;
}