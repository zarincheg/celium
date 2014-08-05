<?php
namespace Celium\Services;
/**
 * Description of Worker
 * 
 * @author Kirill Zorin <zarincheg@gmail.com>
 *
 */

use Celium\Config;
use Celium\DefaultLogger;
use Monolog\Logger;

class Worker extends \GearmanWorker {
	private $function;
	protected $logger;
	protected $id;
	protected $workload;
	protected $mongo;

	public function __construct($function, Logger $logger = null) {
		$this->function = $function;
		$this->mongo = new \MongoClient(Config::$get->database->mongodb);

		if (!$logger) {
			$this->logger = new DefaultLogger('worker');
		} else {
			$this->logger = $logger;
		}
		parent::__construct();
	}

	public function start($server = '127.0.0.1:4730') {
		if(is_array($server)) {
			$this->addServers(implode(',', $server));
		} elseif(is_string($server)) {
			$h = explode(':', $server);
			$this->addServer($h[0], $h[1]);
		} else {
			return false;
		}
		
		$this->addFunction($this->function, array($this, 'process'));
		
		$this->logger->info('Node worker starting', [
			'server' => $server,
			'binding' => $this->function,
			'tags' => ['start', 'worker']
		]);

		while($this->work()) {
			// @todo Error handler
		}
		
		return true;
	}

	public function process(\GearmanJob $job) {
		/*
		 * @todo Schema validation
		 */
		$this->workload = json_decode($job->workload(), true);
		$this->logger->info('Task accepted', [
			'function' => $this->function
		]);

		if (!$this->workload) {
			$this->logger->warning('Workload is empty', [
				'function' => $this->function
			]);
			return false;
		}
		return true;
	}
}