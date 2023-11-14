<?php

namespace uzdevid\websocket\base;

use yii\web\HeaderCollection;
use yii\web\JsonParser;

class Request extends \yii\web\Request {
    public $parsers = [
        'application/json' => JsonParser::class,
    ];

    /**
     * @param array $headers
     *
     * @return void
     */
    public function loadHeaders(array $headers): void {
        foreach ($headers as $key => $value) {
            $this->loadHeader($key, $value);
        }
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return HeaderCollection
     */
    public function loadHeader(string $name, string $value): HeaderCollection {
        return $this->headers->set($name, $value);
    }

    /**
     * @return void
     */
    public function clear(): void {
        $this->setQueryParams(null);
        $this->setBodyParams(null);
        $this->setRawBody(null);
    }
}