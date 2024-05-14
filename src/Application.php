<?php

namespace UzDevid\WebSocket;

use Yii;
use yii\base\InvalidConfigException;
use yii\console\ErrorHandler;

/**
 * @property-read Request $request The request component.
 * @property WebSocket $webSocket
 */
class Application extends \yii\console\Application {
    public $controllerNamespace = 'socket\\controllers';
    public $name = 'My Socket Application';
    private WebSocket $_webSocket;

    /**
     * @return void
     */
    public function run(): void {
        $this->_webSocket->run();
    }

    /**
     * @param array $config
     * @throws InvalidConfigException
     */
    public function setWebSocket(array $config): void {
        $object = Yii::createObject($config);

        if (!($object instanceof WebSocket)) {
            throw new InvalidConfigException("webSocket must be " . WebSocket::class);
        }

        $this->_webSocket = $object;
    }

    /**
     * @return WebSocket
     */
    public function getWebSocket(): WebSocket {
        return $this->_webSocket;
    }

    /**
     * @return class-string[][]
     */
    public function coreComponents(): array {
        return [
            'request' => ['class' => Request::class],
            'errorHandler' => ['class' => ErrorHandler::class],
        ];
    }
}