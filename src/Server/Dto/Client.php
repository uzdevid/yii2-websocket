<?php

namespace UzDevid\WebSocket\Server\Dto;

use Workerman\Connection\TcpConnection;
use yii\web\HeaderCollection;

final class Client {
    public int $id;

    /**
     * @param TcpConnection $tcp
     * @param array $queryParams
     * @param HeaderCollection $headers
     */
    public function __construct(
        public TcpConnection    $tcp,
        public array            $queryParams,
        public HeaderCollection $headers
    ) {
        $this->id = $this->tcp->id;
    }
}