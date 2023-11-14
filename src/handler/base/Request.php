<?php

namespace uzdevid\websocket\handler\base;

use yii\web\IdentityInterface;

class Request {
    protected string $method;
    protected array $headers = [];
    protected array $body = [];

    public IdentityInterface|null $identity = null;

    public function __construct(string $method, array $body, array $headers) {
        $this->method = $method;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getControllerNamespace(string|null $controllerNamespace = null): string {
        return str_replace('.', '/', $this->method);
    }

    public function getMethod(): string {
        return $this->method;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function getBody(): array {
        return $this->body;
    }
}