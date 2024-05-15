<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Client\WebSocketClient;
use UzDevid\WebSocket\Server\Collection\Clients;
use UzDevid\WebSocket\Server\WebSocketServer;
use Yii;
use yii\base\Controller;
use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;
use yii\console\ErrorHandler;

/**
 * @property-write array $webSocketServer
 * @property-read Clients $clients
 */
class Application extends \yii\console\Application {
    public $name = 'My WebSocket Application';
    private WebSocketServer $webSocketServer;
    private WebSocketClient $webSocketClient;

    private Clients $_clients;

    /**
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(array $config = []) {
        $this->_clients = new Clients();

        parent::__construct($config);
    }

    /**
     * @return void
     */
    public function run(): void {
        $this->webSocketServer->run();
    }

    /**
     * @param array $config
     * @throws InvalidConfigException
     */
    public function setWebSocketServer(array $config): void {
        $object = Yii::createObject($config);

        if (!($object instanceof WebSocketServer)) {
            throw new InvalidConfigException("webSocket must be " . WebSocketServer::class);
        }

        $this->webSocketServer = $object;
    }

    /**
     * @return \UzDevid\WebSocket\Server\Collection\Clients
     */
    public function getClients(): Clients {
        return $this->_clients;
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
        return array_merge(parent::coreComponents(), [
            'request' => ['class' => Request::class],
            'response' => ['class' => Response::class],
            'errorHandler' => ['class' => ErrorHandler::class],
        ]);
    }
}