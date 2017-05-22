<?php

namespace devyk\amqp\factories;

use devyk\amqp\components\AmqpMessage;

class AmqpMessageFactory implements FactoryInterface
{
    /**
     * @param array $data
     * @param array $properties
     * @return AmqpMessage
     */
    public function create(array $data = [], array $properties = [])
    {
        return (new AmqpMessage('', $properties))->setBody($data);
    }
}
