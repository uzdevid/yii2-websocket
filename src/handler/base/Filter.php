<?php

namespace uzdevid\websocket;

use uzdevid\websocket\handler\base\Message;
use uzdevid\websocket\handler\base\Request;
use uzdevid\websocket\handler\base\Response;

abstract class Filter {
    private Request $request;
    private Response $response;

    private Method $method;

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