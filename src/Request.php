<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Entity\Message;
use yii\web\HeaderCollection;
use yii\web\JsonParser;

/**
 * @property Message $message
 */
class Request extends \yii\web\Request {
    public Message $message;

    public $parsers = [
        'application/json' => JsonParser::class,
    ];

    /**
     * @param HeaderCollection $headers
     *
     * @return void
     */
    public function loadHeaders(HeaderCollection $headers): void {
        foreach ($headers as $name => $value) {
            $this->headers->set($name, $value);
        }
    }


    /**
     * @return void
     */
    public function clear(): void {
        $this->setQueryParams([]);
        $this->setBodyParams([]);
        $this->setRawBody(null);
    }
}