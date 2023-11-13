<?php

namespace uzdevid\websocket;

use common\exceptions\UnprocessableEntityHttpException;
use uzdevid\websocket\messages\Error;
use uzdevid\websocket\messages\Success;
use yii\base\Exception;

class Router {
    public string $route;
    public array $body;
    public Method $method;
    public Response $response;

    private WebSocket $webSocket;

    public function __construct(string $route, array $body, Response $response) {
        $this->route = $route;
        $this->body = $body;
        $this->response = $response;
    }

    public function webSocket(WebSocket $webSocket): static {
        $this->webSocket = $webSocket;
        return $this;
    }

    public function run(): Message {
        preg_match($this->webSocket->methodPattern, $this->route, $matches);

        $className = str_replace('.', '\\', $matches[1]);
        $className = "{$this->webSocket->methodsNamespace}\\$className";
        $methodName = $matches[2];

        if (!class_exists($className) || !method_exists($className, $methodName)) {
            $this->response->message(new Error('Invalid method'))->send();
        }

        $this->method = new $className($this->response);

        try {
            $responseMessage = call_user_func([$this->method, $methodName], $this->body);
            $responseMessage = new Success($responseMessage);
        } catch (UnprocessableEntityHttpException $exception) {
            $responseMessage = new Error($exception->getMessage(), $exception->errors);
        } catch (Exception $exception) {
            $responseMessage = new Error($exception->getMessage());
        }

        return $responseMessage;
    }
}