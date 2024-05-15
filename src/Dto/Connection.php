<?php

namespace UzDevid\WebSocket\Dto;

use Workerman\Connection\TcpConnection;

final class Connection {
    public int $id;

    /**
     * @param TcpConnection $tcp
     * @param array $queryParams
     * @param array $headers
     * @param int|string|null $client_id
     */
    public function __construct(
        public TcpConnection   $tcp,
        public array           $queryParams = [],
        public array           $headers = [],
        public int|string|null $client_id = null
    ) {
        $this->id = $this->tcp->id;
    }
}