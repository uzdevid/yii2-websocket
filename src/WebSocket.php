<?php

namespace uzdevid\websocket;

use uzdevid\websocket\base\Application;
use uzdevid\websocket\base\ApplicationInterface;
use uzdevid\websocket\base\Dispatcher;
use uzdevid\websocket\client\Clients;
use Workerman\Worker;
use yii\base\Component;
use yii\base\InvalidConfigException;

class WebSocket extends Component {
    public string $protocol = 'websocket';
    public string $host = 'localhost';
    public int $port = 2346;
    public string $url = '';

    public string $clientProtocol = 'ws';
    public string $clientHost = 'localhost';

    public ApplicationInterface|Application|array $app;

    public Clients $clients;

    public function __construct() {
        $this->clients = new Clients();
        parent::__construct();
    }

    /**
     * @throws InvalidConfigException
     */
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

    public function wsClient(): WSClient {
        return new WSClient($this);
    }

    public function clients(): Clients {
        return $this->clients;
    }
}