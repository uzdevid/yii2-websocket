<?php

namespace UzDevid\WebSocket;

use UzDevid\WebSocket\Client\Clients;
use Workerman\Worker;
use yii\base\Component;

class WebSocket extends Component {
    public string $name = 'Main';
    public int $count = 4;
    public string $protocol = 'websocket';
    public string $host = 'localhost';
    public int $port = 2346;
    public string $url = '';

    public string $clientProtocol = 'ws';
    public string $clientHost = 'localhost';

    public Clients $clients;

    public function __construct() {
        $this->clients = new Clients();
        parent::__construct();
    }

    /**
     * @return void
     */
    public function run(): void {
        $address = sprintf('%s://%s:%d/%s', $this->protocol, $this->host, $this->port, $this->url);

        $worker = new Worker($address);

        $worker->name = $this->name;
        $worker->count = $this->count;

        $dispatcher = new Dispatcher($this);

        $worker->onConnect = [$dispatcher, 'onConnect'];

        $worker->onMessage = [$dispatcher, 'onMessage'];

        $worker->onClose = [$dispatcher, 'onClose'];

        Worker::runAll();
    }

    /**
     * @return Clients
     */
    public function clients(): Clients {
        return $this->clients;
    }
}