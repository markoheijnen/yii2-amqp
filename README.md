yii2-amqp
=========

AMQP extension wrapper to communicate with RabbitMQ server. Based on [php-amqplib/php-amqplib](https://github.com/php-amqplib/php-amqplib).
This fork provides an alternative list of the parameters to be able to listen on multiple queues with one worker.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Add the following lines

```
...
"repositories":[
    {
      "type":"git",
      "url":"https://github.com/M0nsterLabs/yii2-migration-aware-module"
    },
],
...
"devyk/yii2-amqp": "1.0.2"
  
```

to your `composer.json` file.

Add the following in your console config:

```php
return [
    ...
    'components' => [
        ...
        'amqp' => [
            'class' => 'devyk\amqp\components\Amqp',
            'host' => '127.0.0.1',
            'port' => 5672,
            'user' => 'your_login',
            'password' => 'your_password',
            'vhost' => '/',
        ],
        ...
    ],
    ...
    'controllerMap' => [
        ...
        'rabbit' => [
            'class' => 'devyk\amqp\controllers\AmqpListenerController',
            'interpreters' => [
                'my-exchange' => 'app\components\RabbitInterpreter', // interpreters for each exchange
            ],
        ],
        ...
    ],
    ...
];
```

Add messages interpreter class `@app/components/RabbitInterpreter` with your handlers for different routing keys:

```php
<?php

namespace app\components;

use devyk\amqp\components\AmqpInterpreter;
use app\services\ExampleServiceInterface;


class RabbitInterpreter extends AmqpInterpreter
{
    protected $service;
    
    /**
     * Example of passing custom service as dependency for the AmqpInterpreter
     */
    public function __construct(ExampleServiceInterface $exampleService)
    {
        $this->service = $exampleService;
        parent::__construct();
    }
    
    /**
     * Interprets AMQP message with routing key 'hello_world'.
     *
     * @param array $message
     */
    public function readHelloWorld($message)
    {
        // todo: write message handler
        $this->log(print_r($message, true));
    }
}
```

## Usage

Just run command

```bash
$ php yii rabbit
```

to listen queue on specified exchange

```bash
$ php yii rabbit <queue_name> --exchange=<exchange_name>
```

to listen multiple queues with one worker

```bash
$ php yii rabbit <queue_name>, <queue_name_1> --exchange=<exchange_name>
```

to enable auto acknowledge

```bash
$ php yii rabbit <queue_name>, <queue_name_1> --noAck=true
```

Also you can create controllers for your needs. Just use for your web controllers class
`devyk\amqp\controllers\AmqpConsoleController` instead of `yii\web\Controller` and for your console controllers
class `devyk\amqp\controllers\AmqpConsoleController` instead of `yii\console\Controller`. AMQP connection will be
available with property `connection`. AMQP channel will be available with property `channel`.
