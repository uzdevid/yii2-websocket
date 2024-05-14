<?php

namespace UzDevid\WebSocket;

use yii\base\InvalidConfigException;

/**
 * @property-read Request $request The request component.
 * @property-read Response $response The response component.
 */
class Application extends \yii\console\Application {
    public $controllerNamespace = 'socket\\socket\\controllers';
    public $name = 'My Socket Application';
    public $defaultRoute = 'socket';

    public WebSocket $webSocket;

    /**
     * @throws InvalidConfigException
     */
    public function run(): void {
        $this->webSocket->run();
    }
}