<?php

namespace uzdevid\websocket\base;

class Application extends \yii\web\Application implements ApplicationInterface {
    public $controllerNamespace = 'socket\\socket\\controllers';
    public $name = 'My Socket Application';
    public $defaultRoute = 'socket';
}