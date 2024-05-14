<?php

namespace uzdevid\WebSocket\Base;

/**
 * @property-read Request $request The request component.
 * @property-read Response $response The response component.
 */
class Application extends \yii\web\Application {
    public $controllerNamespace = 'socket\\socket\\controllers';
    public $name = 'My Socket Application';
    public $defaultRoute = 'socket';
}