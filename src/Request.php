<?php
namespace UzDevid\WebSocket;

use yii\web\HeaderCollection;

class Request extends \yii\web\Request {
    /**
     * @return HeaderCollection
     */
    public function getHeaders(): HeaderCollection {
        $_headers = new HeaderCollection();
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            foreach ($headers as $name => $value) {
                $_headers->add($name, $value);
            }
        } elseif (function_exists('http_get_request_headers')) {
            $headers = http_get_request_headers();
            foreach ($headers as $name => $value) {
                $_headers->add($name, $value);
            }
        } else {
            // ['prefix' => length]
            $headerPrefixes = ['HTTP_' => 5, 'REDIRECT_HTTP_' => 14];

            foreach ($_SERVER as $name => $value) {
                foreach ($headerPrefixes as $prefix => $length) {
                    if (strncmp($name, $prefix, $length) === 0) {
                        $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, $length)))));
                        $_headers->add($name, $value);
                        continue 2;
                    }
                }
            }
        }

        $this->filterHeaders($_headers);

        return $_headers;
    }
}