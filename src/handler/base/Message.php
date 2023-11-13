<?php

namespace uzdevid\websocket\handler\base;

use yii\base\Arrayable;
use yii\base\ArrayableTrait;

abstract class Message implements Arrayable {
    use ArrayableTrait;
    
    public string $method;
}