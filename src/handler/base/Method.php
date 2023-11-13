<?php

namespace uzdevid\websocket;

use uzdevid\websocket\handler\base\Response;

abstract class Method {
    protected Response $response;

    public function __construct(Response $response) {
        $this->response = $response;
    }
}