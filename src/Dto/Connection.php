<?php

namespace UzDevid\WebSocket\Dto;

use Workerman\Connection\TcpConnection;
use Yii;
use yii\web\HeaderCollection;
use yii\web\NotFoundHttpException;

final class Connection {
    public int $id;

    /**
     * @param TcpConnection $tcp
     * @param array $queryParams
     * @param HeaderCollection $headers
     * @param int|string|null $client_id
     */
    public function __construct(
        public TcpConnection   $tcp,
        public array            $queryParams,
        public HeaderCollection $headers,
        public int|string|null $client_id = null
    ) {
        $this->id = $this->tcp->id;
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getClient(): Client {
        return Yii::$app->clients->get($this->client_id);
    }
}