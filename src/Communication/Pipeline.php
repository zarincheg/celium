<?php
namespace Celium\Communication;
use Celium\Config;
use Celium\DefaultLogger;
use Celium\Rabbit;
use Monolog\Logger;

/**
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

class Pipeline implements CeliumNode, CeliumClient {
	/**
	 * @var Имя узла
	 */
	private $name;
	/**
	 * @var string Имя родительского узла
	 */
	private $parentName;
	/**
	 * @var Контейнер для доступа к родительскому узлу. Содержит объекты очередей и хранилища данных
	 */
	private $parentNode;
	/**
	 * @var \AMQPQueue queue of current node
	 */
	private $requestQueue;
	/**
	 * @var \AMQPQueue queue of current node
	 */
	private $notifyQueue;
	/**
	 * @var \MongoCollection
	 */
	private $dataCollection;
	/**
	 * @var \MongoCollection
	 */
	private $indexCollection;
	/**
	 * @var \MongoCollection
	 */
	private $commandsCollection;
	/**
	 * @var \Rabbit
	 */
	private $rabbit;
	/**
	 * @var \Mongo
	 */
	private $mongo;

	/**
	 * @param string $name
	 * @param string|bool $parentName Узел-родитель. Тот, к которому подключаемся. Если не указан, то текущий считается корневым.
	 */
	function __construct($name, $parentName = false) {
		$this->name = $name;

		$this->rabbit = new Rabbit();
		$this->notifyQueue = $this->rabbit->init($this->name.'_notify');
		$this->requestQueue = $this->rabbit->init($this->name.'_request', 'r');

		$this->mongo = new \Mongo(Config::$get->database->mongodb);
		$this->dataCollection = $this->mongo->nodes->selectCollection($name.'_storage');
		$this->indexCollection = $this->mongo->nodes->selectCollection($name.'_index');
		$this->commandsCollection = $this->mongo->nodes->selectCollection($name.'_commands');

		if($parentName) {
			$this->parentName = $parentName;
			$this->initParent($parentName);
		}

		$this->logger = new DefaultLogger('pipeline');
	}

	public function setLogger(Logger $logger) {
		$this->logger = $logger;
	}

	/**
	 * Инициализация коммуникации с предшествующим узлом. Подключение очередей и хранилища
	 * @param string Имя узла
	 * @return void
	 */
	private function initParent($name) {
		$this->parentNode['notifyQueue'] = $this->rabbit->init($name.'_notify', 'r');
		$this->parentNode['requestQueue'] = $this->rabbit->init($name.'_request');
		$this->parentNode['dataCollection'] = $this->mongo->nodes->selectCollection($name.'_storage');
	}

	/**
	 * Fetching result data from Celium Node, by the unique key
	 * @param string $key Unique data key
	 * @return array|null
	 */
	public function getData($key)
	{
		$this->logger->info('Fetch data from node', [
			'nodeName' => $this->name,
			'dataKey' => $key
		]);

		/** @var $collection \MongoCollection */
		$collection = $this->parentNode['dataCollection'];
		return $collection->findOne(['key' => $key], ['_id' => false]);
	}

	public function removeData($key) {
		$this->logger->info('Remove data from node', [
			'nodeName' => $this->name,
			'dataKey' => $key
		]);

		/** @var $collection \MongoCollection */
		$collection = $this->parentNode['dataCollection'];
		return $collection->remove(['key' => $key]);
	}

	/**
	 * Get notification about requested action complete. Returning request/data key for identify needed results.
	 * (which will used in getData() method)
	 * @throws \Exception
	 * @return array|bool Return array with two keys: request key and data key. Data key is for get to result data from storage.
	 */
	public function getNotify()
	{
		if(!$this->parentName)
			throw new \Exception('Precede node not connected!');

		$queue = $this->parentNode['notifyQueue'];
		$message = Rabbit::read($queue);

		if(!$message)
			return false;

		$this->logger->info('Node notification fetched', [
			'nodeName' => $this->name,
			'notifyType' => 'complete',
			'message' => $message
		]);

		return json_decode($message, true);
	}

	/**
	 * Returning request from service client. For run any actions.
	 * @return array|bool
	 */
	public function request()
	{
		$this->logger->info('Receive request from queue', [
			'nodeName' => $this->name
		]);

		return json_decode(Rabbit::read($this->requestQueue), true);
	}

	/**
	 * Sending request to Celium Node for run action
	 * @param string $request
	 * @throws \Exception
	 * @return string Key of request. That's need for find results.
	 */
	public function sendRequest($request)
	{
		if(!$this->parentName)
			throw new \Exception('Precede node not connected!');

		$requestKey = md5($request);
		$request = json_decode($request, true);
		$request['key'] = $requestKey;
		$request = json_encode($request);

		$b = $this->rabbit->write($request, $this->parentName.'_request');

		if(!$b) {
			$this->logger->error('Request to the node failed', [
				'nodeName' => $this->name,
				'requestBody' => $request,
				'requestKey' => $requestKey
			]);

			throw new \Exception("Failed to add the request to the queue");
		}

		$this->logger->info('Request was sent to the node', [
			'nodeName' => $this->name,
			'requestBody' => $request,
			'requestKey' => $requestKey
		]);

		return $requestKey;
	}

	/**
	 * Send notifications about completed action
	 * @param string $message Notification message
	 * @return bool
	 */
	public function notify($message)
	{
		$this->logger->info('Node notification sent', [
			'nodeName' => $this->name,
			'notifyType' => 'complete'
		]);

		return $this->rabbit->write($message, $this->name.'_notify');
	}

	/**
	 * @param $key Unique key for data that is results of action
	 * @param array $data Results of actions
	 * @return bool
	 */
	public function saveData($key, array $data)
	{
		//@todo Это чтобы гарантировать наличие ключа. Потом возможно заменим это схемой документов
		$data['key'] = $key;
		$status = $this->dataCollection->update(['key' => $key], $data, ['upsert' => true]);

		if($status['ok'] !== 1) {
			$this->logger->error('Node data can not save', [
				'nodeName' => $this->name,
				'dataKey' => $key
			]);

			return false;
		}

		$this->logger->info('Node data saved', [
			'nodeName' => $this->name,
			'dataKey' => $key
		]);

		return true;
	}

	/**
	 * Checking completion and data ready
	 * @param string $key Unique key for data that is results of action
	 * @return array|null
	 */
	public function checkData($key)
	{
		return $this->dataCollection->findOne(['key' => $key]);
	}

	/**
	 * Add info about request in storage index.
	 * It's for clients with the same requests, that can await results, but task for this request will not duplicated.
	 * @param string $childRequestKey
	 * @param string $parentRequestKey
	 * @internal param string $key Unique key for request
	 * @return bool
	 */
	public function addToIndex($childRequestKey, $parentRequestKey = null)
	{
		$status = $this->indexCollection->insert(['request_key' => $childRequestKey, 'parent_request_key' => $parentRequestKey]);

		if($status['ok'] !== 1) {
			$this->logger->error('Request info can not save into request index', [
				'nodeName' => $this->name,
				'requestKey' => $childRequestKey,
				'parentRequestKey' => $parentRequestKey
			]);

			return false;
		}

		$this->logger->info('Request info saved into request index', [
			'nodeName' => $this->name,
			'requestKey' => $childRequestKey,
			'parentRequestKey' => $parentRequestKey
		]);

		return true;
	}

	/**
	 * Return indexed keys of requests related with request to parent node
	 * @param string $parentRequestKey Ключ запроса к родительскому узлу
	 * @return \MongoCursor
	 */
	public function getIndexByParent($parentRequestKey) {
		$bindList = [];
		$index = $this->indexCollection->find(['parent_request_key' => $parentRequestKey, 'expired' => ['$exists' => 0]]);
		$index->snapshot();

		while ($index->hasNext()) {
			$bindList[] = $index->getNext();
		}

		$this->indexCollection->update(['parent_request_key' => $parentRequestKey], ['$set' => ['expired' => true]], ['multiple' => true]); // @todo Тут проблемный момент из-за отсутствия транзакций. Надо эту часть обдумать. Просто могут лишние индексы удалиться.

		return $bindList;
	}

	/**
	 * @param $key
	 * @return bool|array
	 */
	public function checkIndex($key)
	{
		return $this->indexCollection->findOne(['request_key' => $key]);
	}

	/**
	 * @param $key
	 * @param array $commands
	 * @return bool
	 */
	public function saveRequestCommands($key, array $commands) {
		$status = $this->commandsCollection->insert(['request_key' => $key, 'request_commands' => $commands]);

		if($status['ok'] !== 1) {
			$this->logger->error('Saving requested command failed', [
				'nodeName' => $this->name,
				'requestKey' => $key,
				'commands' => $commands
			]);

			return false;
		}

		$this->logger->info('Requested commands saved', [
			'nodeName' => $this->name,
			'requestKey' => $key,
			'commands' => $commands
		]);

		return true;
	}

	/**
	 * @param string $key
	 * @return array List of commands
	 */
	public function getRequestCommands($key) {
		$result = $this->commandsCollection->findOne(['request_key' => $key]);
		return $result['request_commands'];
	}

	public function getName() {
		return $this->name;
	}
}