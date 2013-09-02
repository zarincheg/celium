<?php
/**
 * Description of class
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

namespace Celium\Communication;
use Celium\Configure;
use Celium\Rabbit;

class Node implements CeliumNode {
	/**
	 * @var \Rabbit
	 */
	private $rabbit;
	/**
	 * @var \Mongo
	 */
	private $mongo;
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
	 * @param string $name
	 */
	function __construct($name) {
		$this->name = $name;

		$this->rabbit = new Rabbit();
		$this->notifyQueue = $this->rabbit->init($this->name.'_notify');
		$this->requestQueue = $this->rabbit->init($this->name.'_request', 'r');

		$this->mongo = new \Mongo(Configure::$get->database->mongodb);
		$this->dataCollection = $this->mongo->nodes->selectCollection($name.'_storage');
		$this->indexCollection = $this->mongo->nodes->selectCollection($name.'_index');
	}
	/**
	 * Returning request from service client. For run any actions.
	 * @return string
	 */
	public function request()
	{
		return json_decode(Rabbit::read($this->requestQueue), true);
	}

	/**
	 * Send notifications about completed action
	 * @param string $message Notification message
	 * @return bool
	 */
	public function notify($message)
	{
		return $this->rabbit->write($message, $this->name.'_notify');
	}

	/**
	 * @param string $key Unique key for data that is results of action
	 * @param array $data Results of actions
	 * @return bool
	 */
	public function saveData($key, array $data)
	{
		//@todo Это чтобы гарантировать наличие ключа. Потом возможно заменим это схемой документов
		$data['key'] = $key;
		$status = $this->dataCollection->update(['key' => $key], $data, ['upsert' => true]);

		if($status['ok'] !== 1)
			return false;

		return true;
	}

	/**
	 * Checking completion and data ready
	 * @param string $key Unique key for data that is results of action
	 * @return bool|array
	 */
	public function checkData($key)
	{
		return $this->dataCollection->findOne(['request_key' => $key]);
	}

	/**
	 * Add info about request in storage index.
	 * It's for clients with the same requests, that can await results, but task for this request will not duplicated.
	 * @param string $key Unique key for request
	 * @return bool
	 */
	public function addToIndex($key)
	{
		$status = $this->indexCollection->insert(['request_key' => $key]);

		if($status['ok'] !== 1)
			return false;

		return true;
	}

	/**
	 * @param $key
	 * @return bool|array
	 */
	public function checkIndex($key)
	{
		return $this->indexCollection->findOne(['request_key' => $key]);
	}
}