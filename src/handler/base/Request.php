<?php

namespace uzdevid\websocket\handler\base;

class Request {
    protected string $method;
    protected array $headers = [];
    protected array $body = [];

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
}