<?php

namespace uzdevid\websocket\messages;

use uzdevid\websocket\Message;
use yii\base\Arrayable;

class Success extends Message {
    public bool $success = true;
    public string $method;
    public Arrayable|array|null $body;

    public function __construct(string $method, Arrayable|array|null $body) {
        $this->method = $method;
        $this->body = $body;
    }
}