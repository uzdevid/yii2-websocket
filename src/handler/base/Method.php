<?php

namespace uzdevid\websocket\handler\base;

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