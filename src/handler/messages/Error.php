<?php

namespace uzdevid\websocket\handler\messages;

use uzdevid\websocket\handler\base\Message;

class Error extends Message {
    public bool $success = false;
    public string $message;
    public array $errors = [];

    public function __construct(string $message, array $errors = []) {
        $this->message = $message;
        $this->errors = $errors;
    }
}