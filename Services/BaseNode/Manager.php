<?php
namespace Celium\Services\BaseNode;
use \Celium\Communication\Pipeline;

/**
 * Created by JetBrains PhpStorm.
 * User: zarin
 * Date: 8/27/13
 * Time: 2:33 PM
 * To change this template use File | Settings | File Templates.
 */
class Manager extends \Celium\Services\Manager {
	/**
	 * @var \Celium\Communication\Pipeline
	 */
	private $node;

	public function __construct($name) {
		$this->node = new Pipeline($name);
		parent::__construct($name);
	}

	public function process() {
		$request = $this->node->request();

		if(!$request) {
			$this->logger->debug('Waiting for requests');
			return true;
		}

		$requestKey = $request['key'];
		// Check the result data in the storage
		$data = $this->node->checkData($requestKey);

		if($data) {
			$this->node->notify($requestKey);
			$this->logger->info('Result data found, notification sent: '.$requestKey);
			return true;
		}

		// @todo Переименовать в workload
		$request['key'] = $requestKey;
		$request = json_encode($request);

		if(!$this->node->checkIndex($requestKey)) {
			$this->node->addToIndex($requestKey);
			$this->addTaskBackground($this->function, $request);
			$this->logger->info('The task was added: '.$requestKey);
		} else {
			$this->logger->info('Trying to add duplicate task: '.$requestKey);
		}

		return parent::process();
	}
}