<?php
namespace Celium;
/**
 * Class for working with RabbitMQ queues
 *
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

class Rabbit {
	/**
	 * @var \AMQPChannel
	 */
	private $channel;
	/**
	 * @var \AMQPExchange
	 */
	private $exchange;

	function __construct() {
		$connection = new \AMQPConnection();
		$connection->connect();

		if (!$connection->isConnected())
			throw new \AMQPConnectionException('Rabbit is not connected');

		$this->channel = new \AMQPChannel($connection);
		$this->exchange = new \AMQPExchange($this->channel);
		$this->exchange->setName('Celium');
		$this->exchange->setType('direct');
		$this->exchange->declare();
	}

	/**
	 * AMQP queue initialization
	 * @param string $name Queue name
	 * @param string $mode Access mode: r is for reading and w is for writing
	 * @return \AMQPQueue
	 */
	public function init($name, $mode = 'w') {
		$queue = new \AMQPQueue($this->channel);
		$queue->setName($name);
		$queue->declare();

		if($mode == 'r')
			$queue->bind('Celium', $name);

		return $queue;
	}

	/**
	 * @param $message
	 * @param $key
	 * @return bool
	 */
	public function write($message, $key) {
		return $this->exchange->publish($message, $key);
	}

	/**
	 * @param AMQPQueue $queue
	 * @return bool|string
	 */
	public static function read(\AMQPQueue $queue) {
		usleep(10000);
		$envelope = $queue->get(AMQP_AUTOACK);

		if($envelope)
			return $envelope->getBody();

		return false;
	}
}