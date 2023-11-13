<?php

namespace uzdevid\websocket;

use yii\base\Arrayable;
use yii\base\ArrayableTrait;

abstract class Message implements Arrayable {
    use ArrayableTrait;

    public bool $success = true;
}