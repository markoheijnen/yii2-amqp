<?php

namespace devyk\amqp\factories;

interface FactoryInterface
{
    /**
     * @param array $data
     * @return Object
     */
    public function create(array $data = []);
}
