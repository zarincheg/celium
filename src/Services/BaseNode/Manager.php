<?php
namespace Celium\Services\BaseNode;
use \Celium\Communication\Pipeline;

/**
 * @author Kirill Zorin aka Zarin <zarincheg@gmail.com>
 * @todo Перевести коммуникацию на другую реализацию \Communication\Node, базовую, а не pipeline
 */
class Manager extends \Celium\Services\Manager {
	/**
	 * @var \Celium\Communication\Pipeline
	 */
	private $node;

	protected $mongo;

	public function __construct($name) {
		$this->node = new Pipeline($name);
		$this->mongo = new \MongoClient(\Celium\Config::$get->database->mongodb);
		parent::__construct($name);
	}

	public function process() {
		$request = $this->node->request();

		if(!$request) {
			return true;
		}

		$requestKey = $request['key'];
		// Check the result data in the storage
		$data = $this->node->checkData($requestKey);

		if($data) {
			$this->node->notify(json_encode(['data_key' => $requestKey, 'request_key' => $requestKey]));
			$this->logger->info('Result data found, notification sent: '.$requestKey);

			return true;
		}

		$request['key'] = $requestKey;
		$workload = json_encode($request);

		if(!$this->node->checkIndex($requestKey)) {
			$this->node->addToIndex($requestKey);
			$this->addTaskBackground($this->function, $workload);
			$this->logger->info('The task was added: '.$requestKey);
		} else {
			$this->logger->info('Trying to add duplicate task: '.$requestKey);
		}

		return parent::process();
	}
}