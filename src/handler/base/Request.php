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

    public function getMethodNamespace(string $alias): string {
        preg_match('/(\w+(?:\.\w+)*)\.(\w+)/m', $this->method, $matches);
        return $alias . "\\" . str_replace('.', '\\', $matches[1]);
    }

    public function getMethodName(): string {
        preg_match('/(\w+(?:\.\w+)*)\.(\w+)/m', $this->method, $matches);
        return $matches[2];
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