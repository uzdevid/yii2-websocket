<?php

namespace uzdevid\websocket\base;

use yii\web\JsonParser;

class Request extends \yii\web\Request {
    public $parsers = [
        'application/json' => JsonParser::class,
    ];
}