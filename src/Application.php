<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Collection\Clients;
use UzDevid\WebSocket\Server\WebSocketServer;
use Yii;
use yii\base\Controller;
use yii\base\InvalidConfigException;
use yii\base\InvalidRouteException;
use yii\console\ErrorHandler;

/**
 * @property-write array $server
 * @property-read Clients $clients
 */
class Application extends \yii\console\Application {
    public $name = 'My WebSocket Application';
    private WebSocketServer $server;

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
        $this->server->run();
    }

    /**
     * @param array $config
     * @throws InvalidConfigException
     */
    public function setServer(array $config): void {
        $object = Yii::createObject($config);

        if (!($object instanceof WebSocketServer)) {
            throw new InvalidConfigException("webSocket must be " . WebSocketServer::class);
        }

        $this->server = $object;
    }

    /**
     * @return Clients
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