<?php
/**
 * Description of class
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

namespace Services\PipelineNode;

use Communication\Pipeline;

class Client extends \Services\Client {

	/**
	 * @var \Communication\Pipeline
	 */
	protected $node;

	public function __construct($name, $parentName) {
		$this->node = new Pipeline($name, $parentName);
		parent::__construct($name);
	}

	public function process() {
		$request = $this->node->request();

		if(!$request) {
			$this->logger->warn('Request was not received or is empty: '.(string) $request['body']);
			return true;
		}

		$childRequestKey = $request['key'];
		// Check the result data in the storage
		$data = $this->node->checkData($childRequestKey);

		if($data) {
			$this->node->notify($childRequestKey);
			$this->logger->info('Result data found, notification sent: '.$childRequestKey);
			return true;
		}

		if(!$this->node->checkIndex($childRequestKey)) {
			$requestKey = $this->prepareRequest($request['body']);

			if(!is_string($requestKey) || empty($requestKey)) {
				throw new \Exception(__CLASS__."::prepareRequest() must return the key of request");
			}

			// Связываем запрос от дочернего узла с запросом настоящего узла к родительскому.
			// Делается это для того, чтобы после обработки можно было предоставить результаты по тому запросу, по которому их ожидают.
			$this->node->addToIndex($childRequestKey, $requestKey);
		} else {
			$this->logger->info('Trying to add duplicate task: '.$childRequestKey);
		}

		// Получаем уведомления от родительского узла
		$notify = $this->node->getNotify(); // data_key & request_key

		if($notify) {
			$this->logger->info('Response from parent node received. Key: '.$notify['data_key']);
			// @todo Может быть и данные в воркерах получать?
			$parentData = $this->node->getData($notify['data_key']); // data_key

			if(!$parentData) {
				$this->logger->warn('In the parent node data not found. Key: '.$notify['data_key']);
			} else {
				// request_key по нему выкупаем ключи запросов клиентов из индекса. И потом он ухдит как dataKey
				// Это нужно потому что мы выполняем запрос по обработке данных из родительского узла. Но отвечать нам нужно на запрос из дочернего.
				// Поэтому нужно связать результаты с запросом к настоящему узлу дополнительно.
				$parentData['key'] = $notify['key'];
				$this->addTaskBackground($this->function, json_encode($parentData));
			}
		}

		return parent::process();
	}

	/**
	 * Method for prepare request to parent node or doing another actions. Can be redefined in child classes,
	 * but must return string with unique key of request. By default return the result of \Pipeline::sendRequest() call, and it is good practice.
	 * @param $requestBody
	 * @return string
	 */
	protected function prepareRequest($requestBody) {
		$this->logger->debug('Prepare request to parent node: '. md5($requestBody));
		return $this->node->sendRequest($requestBody);
	}
}