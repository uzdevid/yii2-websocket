<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Entity\Message;
use yii\web\HeaderCollection;
use yii\web\JsonParser;

/**
 * @property string $message
 */
class Request extends \yii\web\Request {
    private Message $_message;
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
     * @return Message
     */
    public function getMessage(): Message {
        return $this->_message;
    }

    /**
     * @param Message $message
     *
     * @return void
     */
    public function setMessage(Message $message): void {
        $this->_message = $message;
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