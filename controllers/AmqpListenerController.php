<?php
/**
 * @link https://github.com/webtoucher/yii2-amqp
 * @copyright Copyright (c) 2014 webtoucher
 * @license https://github.com/webtoucher/yii2-amqp/blob/master/LICENSE.md
 */

namespace devyk\amqp\controllers;

use yii;
use yii\console\Exception;
use yii\helpers\Inflector;
use yii\helpers\Json;
use PhpAmqpLib\Message\AMQPMessage;
use devyk\amqp\components\AmqpInterpreter;

/**
 * AMQP listener controller.
 *
 * @author Alexey Kuznetsov <mirakuru@webtoucher.ru>
 * @since 2.0
 */
class AmqpListenerController extends AmqpConsoleController
{
    /**
     * Interpreter classes for AMQP messages. This class will be used if interpreter class not set for exchange.
     *
     * @var array
     */
    public $interpreters = [];

    public function actionRun(array $queueNames = [])
    {
        $this->amqp->listen($queueNames, [$this, 'callback'], $this->noAck);
    }

    public function callback(AMQPMessage $msg)
    {
        $routingKey = $msg->delivery_info['routing_key'];
        $method = 'read' . Inflector::camelize($routingKey);

        if (!isset($this->interpreters[$this->exchange])) {
            $interpreter = $this;
        } elseif (class_exists($this->interpreters[$this->exchange])) {
            $interpreter = Yii::createObject($this->interpreters[$this->exchange]);
            if (!$interpreter instanceof AmqpInterpreter) {
                throw new Exception(sprintf("Class '%s' is not correct interpreter class.", $this->interpreters[$this->exchange]));
            }
        } else {
            throw new Exception(sprintf("Interpreter class '%s' was not found.", $this->interpreters[$this->exchange]));
        }

        if (method_exists($interpreter, $method)) {
            $interpreter->$method(
                Json::decode($msg->body, true),
                $msg->delivery_info['channel'],
                $msg->delivery_info['delivery_tag']
            );
        } else {
            if (!isset($this->interpreters[$this->exchange])) {
                $interpreter = new AmqpInterpreter();
            }
            $interpreter->log(
                sprintf("Unknown routing key '%s' for exchange '%s'.", $routingKey, $this->exchange),
                $interpreter::MESSAGE_ERROR
            );
        }
    }
}
