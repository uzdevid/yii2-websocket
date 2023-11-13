<?php

namespace uzdevid\websocket;

use uzdevid\websocket\handler\base\Filter;
use uzdevid\websocket\handler\base\Response;

abstract class Method {
    protected Response $response;

    public function __construct(Response $response) {
        $this->response = $response;
    }

    /**
     * @return Filter[]
     */
    public function filters(): array {
        return [];
    }
}