<?php

namespace uzdevid\websocket\handler\base;

use uzdevid\websocket\WebSocket;
use Yii;
use yii\base\InvalidRouteException;
use yii\console\Exception;

class Router {
    public Request $request;
    public Response $response;
    private WebSocket $webSocket;

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function webSocket(WebSocket $webSocket): static {
        $this->webSocket = $webSocket;
        return $this;
    }

    /**
     * @return Message
     * @throws InvalidRouteException
     * @throws Exception
     */
    public function run(): Message {
        $path = $this->request->getControllerNamespace($this->webSocket->methodsNamespace);
        
        return Yii::$app->runAction($path, [
            'request' => $this->request,
            'response' => $this->response
        ]);
    }
}