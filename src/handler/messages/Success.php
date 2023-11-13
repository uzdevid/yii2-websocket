<?php

namespace uzdevid\websocket\handler\messages;

use uzdevid\websocket\handler\base\Message;
use yii\base\Arrayable;

class Success extends Message {
    public bool $success = true;
    public Arrayable|array|null $body;

    public function __construct(Arrayable|array|null $body) {
        $this->body = $body;
    }
}