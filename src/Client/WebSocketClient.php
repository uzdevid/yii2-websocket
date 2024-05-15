<?php

namespace UzDevid\WebSocket\Client;

use WebSocket\Client;
use yii\base\Arrayable;
use yii\base\Component;
use yii\helpers\Json;

class WebSocketClient extends Component {
    public string $protocol = 'ws';
    public string $host = 'localhost';
    public int $port = 2346;
    public string $url = '';

    public array $options = [];

    private Client $client;

    /**
     * @param array $config
     */
    public function __construct(array $config = []) {
        parent::__construct($config);

        $address = sprintf('%s://%s:%d/%s', $this->protocol, $this->host, $this->port, $this->url);
        $this->client = new Client($address, $this->options);
    }

    /**
     * @param string $method
     * @param Arrayable|array $payload
     * @return void
     */
    public function send(string $method, Arrayable|array $payload): void {
        $encodedPayload = JSON::encode(compact('method', 'payload'), JSON_UNESCAPED_UNICODE);
        $this->client->text($encodedPayload);
    }

    /**
     * @return Client
     */
    public function getClient(): Client {
        return $this->client;
    }
}