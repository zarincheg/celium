<?php
/**
 * Description of class
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

namespace Communication;

class Client implements CeliumClient {

	/**
	 * @var \Rabbit
	 */
	private $rabbit;
	/**
	 * @var \AMQPQueue queue of current node
	 */
	private $requestQueue;
	/**
	 * @var \AMQPQueue queue of current node
	 */
	private $notifyQueue;
	/**
	 * @var string Node name
	 */
	private $name;
	/**
	 * @var \MongoCollection
	 */
	private $dataCollection;
	/**
	 * @var \Mongo
	 */
	private $mongo;

	function __construct($name) {
		$this->name = $name;

		$this->rabbit = new \Rabbit();
		$this->requestQueue = $this->rabbit->init($this->name.'_request', 'r');
		$this->notifyQueue = $this->rabbit->init($this->name.'_notify', 'r');

		$this->mongo = new \Mongo(\Configure::$get->database->mongodb);
		$this->dataCollection = $this->mongo->nodes->selectCollection($name.'_storage');
	}

	/**
	 * Sending request to Celium Node for run action
	 * @param string $request
	 * @throws \Exception
	 * @return string Key of request. That's need for find results.
	 */
	public function sendRequest($request)
	{
		$requestKey = md5($request);
		$request = json_decode($request, true);
		$request['key'] = $requestKey;

		$request = json_encode($request);

		$b = $this->rabbit->write($request, $this->name.'_request');

		if(!$b)
			throw new \Exception("Failed to add the request to the queue");

		return $requestKey;
	}

	/**
	 * Fetching result data from Celium Node, by the unique key
	 * @param string $key Unique data key
	 * @return array|null
	 */
	public function getData($key)
	{
		/** @var $collection \MongoCollection */
		$collection = $this->dataCollection;
		return $collection->findOne(['key' => $key], ['_id' => false]);
	}

	/**
	 * Get notification about requested action complete. Returning request/data key for identify needed results.
	 * (which will used in getData() method)
	 * @throws \Exception
	 * @return array|bool
	 */
	public function getNotify()
	{
		$message = \Rabbit::read($this->notifyQueue);

		if(!$message)
			return false;

		return json_decode($message, true);
	}
}