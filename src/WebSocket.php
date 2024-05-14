<?php

namespace uzdevid\WebSocket;

use uzdevid\WebSocket\Base\Application;
use uzdevid\WebSocket\Base\ApplicationInterface;
use uzdevid\WebSocket\Base\Dispatcher;
use uzdevid\WebSocket\Client\Clients;
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
    public function run(string $name = 'Main', int $count = 4): void {
        $address = sprintf('%s://%s:%d/%s', $this->protocol, $this->host, $this->port, $this->url);
        $worker = new Worker($address);

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