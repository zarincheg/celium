<?php
namespace Services\BaseNode;
use Communication\Pipeline;

/**
 * @author Kirill Zorin aka Zarin <zarincheg@gmail.com>
 *
 */
class Worker extends \Services\Worker {
	/**
	 * @var \Communication\Pipeline
	 */
	protected $node;
	/**
	 * @var array Results of command execution
	 */
	protected $results;

	public function __construct($name) {
		$this->node = new Pipeline($name);
		parent::__construct($name);
	}

	public function process(\GearmanJob $job) {
		if(!parent::process($job))
			return true;

		if(!isset($this->workload['commands']) || !is_array($this->workload['commands'])) {
			$this->logger->warn('Nothing to do, commands list is empty. Request key: '.$this->workload['key']);
			return true;
		}

		foreach($this->workload['commands'] as $c) {
			$name = $c['name'];
			$this->logger->info('Prepare command: '.$name);

			$command = \CommandRegistry::get($name);
			$this->results[$name] = $command->execute($c['params']);
		}

		try {
			$this->saveResults();
		} catch(\MongoException $e) {
			$this->logger->fatal('Failed to save the results of data processing. Worker: '.__CLASS__);
		}

		$notify = ['request_key' => $this->workload['key'], 'data_key' => $this->workload['key']];
		$this->node->notify(json_encode($notify));
		$this->logger->info("Task complete. Request key: ". $this->workload['key']);

		return true;
	}

	/**
	 * Method for handle data saving logic. Can be redefined in child classes.
	 */
	protected function saveResults() {
		$this->node->saveData($this->workload['key'], ['data' => $this->results]);
	}
}