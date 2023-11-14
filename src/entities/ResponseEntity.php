<?php

namespace uzdevid\websocket\entities;

use uzdevid\property\loader\Entity;
use uzdevid\websocket\base\Response;
use yii\base\Arrayable;

class ResponseEntity extends Entity {
    public bool $success = true;
    public Arrayable|array|null $body;

    public function __construct(Response $response) {
        parent::__construct([
            'success' => $response->isSuccessful,
            'body' => $response->data,
        ]);
    }

    public static function name(): string {
        return 'Response';
    }
}