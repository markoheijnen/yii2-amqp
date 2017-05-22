<?php

namespace devyk\amqp\factories;

use yii\helpers\ArrayHelper;
use devyk\amqp\components\AmqpMessage;

class AmqpMessageFactory implements FactoryInterface
{
    /**
     * @param array $config
     * @return AmqpMessage
     */
    public function create(array $config = [])
    {
        $message = new AmqpMessage('', ArrayHelper::getValue($config, 'properties', []));
        return $message->setBody(ArrayHelper::getValue($config, 'body', []));
    }
}
