<?php

namespace UzDevid\WebSocket;

use Yii;
use yii\base\Controller;
use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;
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
     * @param $route
     * @param array $params
     * @return mixed|null
     * @throws InvalidConfigException
     * @throws InvalidRouteException
     */
    public function runAction($route, $params = []): mixed {
        $parts = $this->createController($route);

        if (!is_array($parts)) {
            $id = $this->getUniqueId();
            throw new InvalidRouteException('Unable to resolve the request "' . ($id === '' ? $route : $id . '/' . $route) . '".');
        }

        /* @var $controller Controller */
        [$controller, $actionID] = $parts;

        $oldController = Yii::$app->controller;

        Yii::$app->controller = $controller;

        $result = $controller->runAction($actionID, $params);

        if ($oldController !== null) {
            Yii::$app->controller = $oldController;
        }

        return $result;
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