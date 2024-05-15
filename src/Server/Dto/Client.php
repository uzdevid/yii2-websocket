<?php

namespace UzDevid\WebSocket\Server\Dto;

use Workerman\Connection\TcpConnection;
use Yii;
use yii\base\BaseObject;
use yii\web\HeaderCollection;
use yii\web\NotFoundHttpException;

/**
 *
 * @property-read User $user
 */
final class Client extends BaseObject {
    public int $id;

    /**
     * @param TcpConnection $tcp
     * @param array $queryParams
     * @param HeaderCollection $headers
     * @param int|string $userId
     */
    public function __construct(
        public TcpConnection    $tcp,
        public array            $queryParams,
        public HeaderCollection $headers,
        private int|string      $userId
    ) {
        $this->id = $this->tcp->id;

        parent::__construct();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getUser(): User {
        return Yii::$app->users->get($this->userId);
    }
}