<?php
/**
 * Description of class
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

namespace Celium\Services\PipelineNode;

use \Celium\Communication\Pipeline;

class Manager extends \Celium\Services\Manager {

	/**
	 * @var \Celium\Communication\Pipeline
	 */
	protected $node;
	protected $mongo;
	protected $name;
	protected $parentName;

	public function __construct($name, $parentName) {
		$this->name = $name;
		$this->parentName = $name;

		$this->node = new Pipeline($name, $parentName);
		$this->mongo = new \MongoClient(\Celium\Config::$get->database->mongodb);
		parent::__construct($name);
	}

	public function process() {
		$request = $this->node->request();

		if($request)
			$this->handleRequest($request);

		// Получаем уведомления от родительского узла
		$notify = $this->node->getNotify(); // data_key & request_key

		if($notify)
			$this->handleNotify($notify);

		return parent::process();
	}

	protected function handleRequest(array $request) {
		$this->logger->info('Handling request from queue', [
			'node' => $this->name,
			'parentNode' => $this->parentName
		]);

		$childRequestKey = $request['key'];
		// Check the result data in the storage
		$data = $this->node->checkData($childRequestKey);

		if($data) {
			$this->node->notify(json_encode(['data_key' => $childRequestKey, 'request_key' => $childRequestKey]));
			$this->logger->info('Result data found, notification sent', ['child_request_key' => $childRequestKey]);

			return true;
		}

		// @todo Возможно ли возникновение разных запросов к родительскому узлу при одинаковых запросах клиентских узлов?!
		if(!$this->node->checkIndex($childRequestKey)) {
			$requestKey = $this->prepareRequest($request);

			if(!is_string($requestKey) || empty($requestKey)) {
				throw new \Exception(__CLASS__."::prepareRequest() must return the key of request");
			}

			// Связываем запрос от дочернего узла с запросом настоящего узла к родительскому.
			// Делается это для того, чтобы после обработки можно было предоставить результаты по тому запросу, по которому их ожидают.
			$this->node->addToIndex($childRequestKey, $requestKey);
			// Теперь нужно здесь же по уведомлению вытаскивать все запросы клиентов и их комманды и уже ставить таск с ними.
			// Таким образом отпадает необходимость в data_key и можно по-прежнему использовать request_key в качестве идентификатора данных
			// + универсальный воркер по идее будет
			$this->node->saveRequestCommands($childRequestKey, $request['commands']);
		} else {
			$this->logger->info('Trying to add duplicate task', ['child_request_key' => $childRequestKey]);
		}
	}

	protected function handleNotify($notify) {
		$this->logger->info('Response from parent node received', ['key' => $notify['data_key']]);
		// @todo Может быть и данные в воркерах получать?
		$parentData = $this->node->getData($notify['data_key']); // data_key

		if(!$parentData) {
			$this->logger->warn('In the parent node data not found', ['key' => $notify['data_key']]);
		} else {
			// request_key по нему выкупаем ключи запросов клиентов из индекса. И потом он ухдит как dataKey
			// Это нужно потому что мы выполняем запрос по обработке данных из родительского узла. Но отвечать нам нужно на запрос из дочернего.
			// Поэтому нужно связать результаты с запросом к настоящему узлу дополнительно.
			//$parentData['key'] = $notify['request_key'];

			$index = $this->node->getIndexByParent($notify['request_key']);

			// Processing the client request directly
			foreach ($index as $keys) {
				$commands = $this->node->getRequestCommands($keys['request_key']); // request_key == client(child)_request_key
				$workload = ['key' => $keys['request_key'],
					'commands' => $commands,
					'parent_node_result' => $parentData]; // @todo Добавить описание схемы для ворклоада пайплайнов
				$this->addTaskBackground($this->function, json_encode($workload));
			}
		}
	}

	/**
	 * Method for prepare request to parent node or doing another actions. Can be redefined in child classes,
	 * but must return string with unique key of request. By default return the result of \Pipeline::sendRequest() call, and it is good practice.
	 * In this method defines which commands will requested for parent node. By default client request just resend.
	 * @param array $request Client request
	 * @return string
	 */
	protected function prepareRequest(array $request) {
		$this->logger->debug('Prepare request to parent node', ['key' => md5($request['key'])]);
		return $this->node->sendRequest(json_encode($request));
	}
}