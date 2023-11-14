<?php

namespace uzdevid\websocket\base;

class SocketApplication extends \yii\web\Application {
    public $controllerNamespace = 'socket\\socket\\controllers';
    public $name = 'My Socket Application';
    public $defaultRoute = 'socket';
}