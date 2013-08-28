<?php
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
	 * Инициализация очереди сообщений
	 * @param string Название очереди
	 * @param string Параметр, указвыающий тип действий с этой очередью. w - запись, r - чтение
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

	public function write($message, $key) {
		return $this->exchange->publish($message, $key);
	}

	public static function read(\AMQPQueue $queue) {
		usleep(10000);
		$envelope = $queue->get(AMQP_AUTOACK);

		if($envelope)
			return $envelope->getBody();

		return false;
	}
}