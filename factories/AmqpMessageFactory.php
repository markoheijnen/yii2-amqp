<?php

namespace devyk\amqp\factories;

use devyk\amqp\components\AmqpMessage;

class AmqpMessageFactory implements FactoryInterface
{
    /**
     * @param array $data
     * @return AmqpMessage
     */
    public function create(array $data = [])
    {
        return (new AmqpMessage())->setBody($data);
    }
}
