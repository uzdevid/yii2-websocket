<?php

namespace UzDevid\WebSocket;

use Yii;
use yii\base\InvalidConfigException;

/**
 * @property-read Request $request The request component.
 * @property-read Response $response The response component.
 */
class Application extends \yii\console\Application {
    public $controllerNamespace = 'socket\\socket\\controllers';
    public $name = 'My Socket Application';
    public $defaultRoute = 'socket';

    private WebSocket $_webSocket;

    /**
     * @throws InvalidConfigException
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
}