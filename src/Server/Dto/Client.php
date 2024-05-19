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
    public string $id;

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
        private readonly int|string $userId
    ) {
        $this->id = self::getUid($this->tcp);

        parent::__construct();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getUser(): User {
        return Yii::$app->users->get($this->userId);
    }

    /**
     * @param TcpConnection $tcpConnection
     * @return string
     */
    public static function getUid(TcpConnection $tcpConnection): string {
        return md5(sprintf("%d:%d", $tcpConnection->id, $tcpConnection->worker->id));
    }
}