<?php

namespace devyk\amqp\components;

use PhpAmqpLib\Message\AMQPMessage as BaseAMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class AmqpMessage extends BaseAMQPMessage
{
    /**
     * @param array $body
     * @return $this
     */
    public function setBody($body)
    {
        parent::setBody(json_encode($body));
        return $this;
    }

    /**
     * @param $delay
     * @return $this
     */
    public function setDelay($delay)
    {
        $this->set(
            'application_headers',
            new AMQPTable([
                'x-delay' => $delay * 1000
            ])
        );
        return $this;
    }
}