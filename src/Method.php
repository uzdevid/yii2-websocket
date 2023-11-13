<?php

namespace uzdevid\websocket;

abstract class Method {
    protected Response $response;

    public function __construct(Response $response) {
        $this->response = $response;
    }
}