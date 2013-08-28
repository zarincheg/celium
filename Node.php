<?php
/**
 * Node class for SDPM v0.1
 *
 * @todo Работает только в рамках одного сервера
 *
 * Тут получается исключение из ноды вычислительного компонента. Который по сути выступает в роли менеджера данных.
 * В дальнейшем нужно этот впорос обдумать и решить.
 * Таким образом данный классс позволяет работать с очередями запросов и уведомлений, а также с хранилищем результатов обработки.
 *
 * Учитывая контекст системы, вся работа с очередями ведется через RabbitMQ, а с данными через MongoDB
 * Во всех документах, которые хранятся в хранилищах должно присутствовать поле key
 *
 * @author Kirill Zorin <zarincheg@gmail.com>
 */
class Node {
	private $name;
	/**
	 * @var string Имя предшествующей очереди
	 */
	private $precede = false;

	/**
	 * RabbitMQ options
	 * 
	 * Узел работает максимум с 4 очередями:
	 * 1. Очередь уведомлений предшествующего узла (read)
	 * 2. Очередь запросов предшествующего узла (write)
	 * 3. Собственная очередь уведомлений (write)
	 * 4. Собственная очередь запросов (read)
	 */
	
	/**
	 * @var \AMQPChannel
	 */
	private $channel;
	/**
	 * @var \AMQPExchange
	 */
	private $exchange;
	/**
	 * @var array Очереди предшествующего узла
	 */
	private $precedeNode = ['notify' => false, 'request' => false];
	/**
	 * @var \AMQPQueue Очередь уведомлений
	 */
	private $notify;
	/**
	 * @var \AMQPQueue Очередь запросов
	 */
	private $request;

	/**
	 * Store options (MongoDB)
	 */

	/**
	 * @var \Mongo
	 */
	private $mongo = false;
	/**
	 * @var array Хранилища результатов. current - для данного узла. precede - для предшествующего
	 * Элементы массива имеют тип \MongoCollection
	 * Тут нужно работу с хранилищами как-то разрулить. А то получается у нас ничего не масштабируется по серверам сейчас.
	 */
	private $storage = ['current' => false, 'precede' => false, 'meta' => false];

	/**
	 * @param string Название узла
	 * @param string | bool Узел-родитель. Тот, к которому подключаемся. Если не указан, то текущий считается корневым.
	 */
	function __construct($name, $precede = false) {
		$this->name = $name;

		$connection = new \AMQPConnection();
		$connection->connect();

		if (!$connection->isConnected())
			throw new \AMQPConnectionException('Rabbit is not connected');

		$this->channel = new \AMQPChannel($connection);
		$this->exchange = new \AMQPExchange($this->channel);
		$this->exchange->setName('SDPM');
		$this->exchange->setType('direct');
		$this->exchange->declare();
		
		$this->notify = $this->initQueue($this->name.'_notify');
		$this->request = $this->initQueue($this->name.'_request', 'r');
		
		$this->mongo = new \Mongo(\Configure::$get->database->mongodb);
		$this->storage['current'] = $this->mongo->nodes->selectCollection($name.'_storage');
		$this->storage['meta'] = $this->mongo->nodes->selectCollection('meta');

		if($precede) {
			$this->precede = $precede;
			$this->initPrecede($precede);
		}
	}

	/**
	 * Инициализация очереди сообщений
	 * @param string Название очереди
	 * @param string Параметр, указвыающий тип действий с этой очередью. w - запись, r - чтение
	 * @return \AMQPQueue
	 */
	private function initQueue($name, $mode = 'w') {
		$queue = new \AMQPQueue($this->channel);
		$queue->setName($name);
		$queue->declare();

		if($mode == 'r')
			$queue->bind('SDPM', $name);

		return $queue;
	}

	/**
	 * Инициализация коммуникации с предшествующим узлом. Подключение очередей и хранилища
	 * @param string Имя узла
	 * @return void
	 */
	private function initPrecede($name) {
		$this->precedeNode['notify'] = $this->initQueue($name.'_notify', 'r');
		$this->precedeNode['request'] = $this->initQueue($name.'_request');
		$this->storage['precede'] = $this->mongo->nodes->selectCollection($name.'_storage');
	}

	private function queueRead(\AMQPQueue $queue) {
		usleep(10000);
		$envelope = $queue->get(AMQP_AUTOACK);

		if($envelope)
			return $envelope->getBody();

		return false;
	}

	/**
	 * Возвращает имя узла
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Чтение уведомлений из очереди уведомлений родительского узла
	 * @throws \Exception Когда нет привязанного узла
	 * @return string | bool Возвращает сообщение из очереди уведомлений предшествующего узла или false в случае пустой очереди
	 */
	public function readNotify() {
		if(!$this->precede)
			throw new \Exception('Precede node not connected!');

		$queue = $this->precedeNode['notify'];
		return $this->queueRead($queue);
	}
	
	/**
	 * Запись уведомлений в очередь
	 * @param string Сообщение уведомления
	 * @return bool
	 */
	public function writeNotify($message) {
		return $this->exchange->publish($message, $this->name.'_notify');
	}

	/**
	 * Чтение запроса из очереди запросов
	 * @return string | bool Возвращает сообщение из очереди запросов или false в случае пустой очереди
	 */
	public function readRequest() {
		return $this->queueRead($this->request);
	}

	/**
	 * Запись запроса в очередь родительского узла
	 * @param string Содержимое запроса
	 * @throws \Exception Когда нет привязанного узла
	 * @return bool Возвращает true, если запись удалась, иначе false
	 */
	public function writeRequest($message) {
		if(!$this->precede)
			throw new \Exception('Precede node not connected!');

		return $this->exchange->publish($message, $this->precede.'_request');
	}

	/**
	 * Чтение данных из хранилища результатов родительского узла
	 * @param string Ключ для идентификации и доступа запросов и результатов. По нему будет произведена выборка.
	 * @return array Массив с данными результатов(decoded JSON)
	 */
	public function readResult($key) {
		/** @var $collection \MongoCollection */
		$collection = $this->storage['precede'];
		return $collection->findOne(['key' => $key], ['_id' => false]);
	}

	/**
	 * Запись результатов вычислений в хранилище
	 */
	public function writeResult($key, array $data) {
		/** @var $collection \MongoCollection */
		$collection = $this->storage['current'];

		// Это чтобы гарантировать наличие ключа. Потом возможно заменим это схемой документов
		$data['key'] = $key;
		$status = $collection->update(['key' => $key], $data, ['upsert' => true]);
		
		if($status['ok'] == 1)
			return true;
		else
			false;
	}

	/**
	 * Проверяем наличие в хранилище результата работы узла по ключу
	 * @param string Ключ для идентификации данных, по нему выборка делается
	 * @return array | bool Возвращает данные из хранилища, или false
	 */
	public function checkResult($key) {
		/** @var $collection \MongoCollection */
		$collection = $this->storage['current'];
		return $collection->findOne(['key' => $key]);
	}

	/**
	 * Запись метаданных, для хранения информации об обрабатываемых данных
	 * @param string Ключ
	 * @param array Массив с необходимыми метаданными
	 * @return bool
	 */
	public function writeMeta($key, array $meta) {
		/** @var $collection \MongoCollection */
		$collection = $this->storage['meta'];
		$status = $collection->insert(['key' => $key, 'meta' => $meta], ['w' => 1]);
		
		if($status['ok'] == 1)
			return true;
		else
			false;
	}

	/**
	 * Чтение мета данных
	 * @param string Ключ
	 * @return array|null
	 */
	public function readMeta($key) {
		/** @var $collection \MongoCollection */
		$collection = $this->storage['meta'];
		return $collection->findOne(['key' => $key], ['meta' => true, '_id' => false]);
	}
}