<?php

namespace uzdevid\websocket\handler\base;

use common\exceptions\UnprocessableEntityHttpException;
use uzdevid\websocket\Filter;
use uzdevid\websocket\messages\Error;
use uzdevid\websocket\messages\Success;
use uzdevid\websocket\Method;
use uzdevid\websocket\WebSocket;
use yii\base\Exception;

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

    public function run(): Message {
        $className = $this->request->getMethodNamespace($this->webSocket->methodsNamespace);
        $methodName = $this->request->getMethodName();

        if (!class_exists($className) || !method_exists($className, $methodName)) {
            $this->response->message(new Error('Method not found'))->send();
        }

        /** @var Method $method */
        $method = new $className($this->response);

        foreach ($method->filters() as $filter) {
            $filterResult = $filter
                ->request($this->request)
                ->response($this->response)
                ->method($method)
                ->run();

            if ($filterResult !== true) {
                return $filterResult;
            }
        }

        try {
            $responseMessage = call_user_func([$method, $methodName], $this->request, $this->webSocket);
            $responseMessage = new Success($responseMessage);
        } catch (UnprocessableEntityHttpException $exception) {
            $responseMessage = new Error($exception->getMessage(), $exception->errors);
        } catch (Exception $exception) {
            $responseMessage = new Error($exception->getMessage());
        }

        return $responseMessage;
    }
}