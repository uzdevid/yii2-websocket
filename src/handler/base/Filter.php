<?php

namespace uzdevid\websocket\handler\base;

use uzdevid\websocket\Method;

abstract class Filter {
    protected Request $request;
    protected Response $response;
    protected Method $method;

    public function request(Request $request): static {
        $this->request = $request;
        return $this;
    }

    public function response(Response $response): static {
        $this->response = $response;
        return $this;
    }

    public function method(Method &$method): static {
        $this->method = $method;
        return $this;
    }

    abstract public function run(): Message|bool;
}