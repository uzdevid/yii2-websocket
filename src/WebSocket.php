<?php

namespace uzdevid\websocket;

use Workerman\Worker;
use yii\base\Component;

class WebSocket extends Component {
    public string $protocol = 'websocket';
    public string $host = '';
    public int $port = 2346;
    public string $url = '';

    public string $methodPattern = '/(\w+(?:\.\w+)*)\.(\w+)/m';
    public string $methodsNamespace = 'console\\socket\\methods';

    public function run(string $name = 'main', int $count = 4): void {
        $worker = new Worker("{$this->protocol}://{$this->host}:{$this->port}/{$this->url}");

        $worker->name = $name;
        $worker->count = $count;

        $dispatcher = new Dispatcher($this);

        $worker->onConnect = [$dispatcher, 'onConnect'];

        $worker->onMessage = [$dispatcher, 'onMessage'];

        $worker->onClose = [$dispatcher, 'onClose'];

        Worker::runAll();
    }
}