# WebSocket Application for Yii2 framework

Yii2 WebSocket Application is a powerful and flexible extension for the Yii2 framework that enables developers to integrate a WebSocket server into their applications. Built on top of the `workerman/workerman` package, this extension is ideal for creating real-time features such as chats, notifications, online games, and other interactive applications that require a persistent connection to the server.

### Installation

```bash
composer require uzdevid/yii2-websocket
```

### Usage

Application configuration similar to the default application.

Create config file `<project_root>/socket/config/main.php` 

```php
use UzDevid\WebSocket\Server\WebSocketServer;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php',
);

return [
    'id' => 'web-socket-app',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'socket\\controllers',
    'webSocketServer' => [
        'class' => WebSocketServer::class,
        'host' => '0.0.0.0',
        'port' => 8080,
        'count' => 1
    ],
    'components' => [],
    'params' => $params,
];
```

With the current settings, the web socket server will listen on `0.0.0.0:8080`. Count of workers: 1

---
Message processing occurs through the controller and action. Create `<project-root>/socket/EchoController` controller.

```php
namespace socket\controllers;

use UzDevid\WebSocket\Controller;
use UzDevid\WebSocket\Server\Dto\Client;

class EchoController extends Controller {
    /**
     * @param Client $client
     * @param array $payload
     * @return void
     */
    public function actionEcho(Client $client, array $payload): void {
        $client->user->send('echo:echo', ['currentTime' => time()]);
    }
}
```

---

Create entry point file named `<project_root>/run`

```php
#!/usr/bin/env php
/**
 * Yii WebSocket bootstrap file.
 */
use UzDevid\WebSocket\Application;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/common/Config/bootstrap.php';
require __DIR__ . '/socket/Config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/common/Config/main.php',
    require __DIR__ . '/common/Config/Local/main.php',
    require __DIR__ . '/socket/Config/main.php',
);

$application = new Application($config);
$application->run();
```

---

To start the server you need to run the command:

```bash
php <project_root>/run start
```

For testing, we can use the Postman program. You need to create a web socket connection. You need to create a web socket connection with port `ws://0.0.0.0:8080`, which is specified in the application configuration.

Message Body Format. 
```json
{
  "method": "echo:echo",
  "payload": {}
}
```
The `method` parameter is similar to a regular url, the only difference is in the separator `:` What is specified in the payload parameter You can get them in the action arguments.