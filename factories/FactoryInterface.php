<?php

namespace devyk\amqp\factories;

interface FactoryInterface
{
    /**
     * @param array $config
     * @return Object
     */
    public function create(array $config = []);
}
