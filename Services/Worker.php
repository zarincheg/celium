<?php
namespace Gearman;
/**
 * Description of Worker
 * 
 * @author Kirill Zorin <zarincheg@gmail.com>
 * @copyright Copyright (c) 2011, SeoStopol
 * 
 */
class Worker extends \GearmanWorker {
	private $function;
	protected $logger;
	protected $id;
	protected $workload;

	public function __construct($function) {
		$this->function = $function;
		$this->logger = \Logger::getRootLogger();
		$this->id = rand(100, 99999);
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
		
		$this->logger->info('Gearman worker starting. Server: '.$server.'. Bind function: '.$this->function);

		while($this->work()) {
			// @todo Error handler
		}
		
		return true;
	}

	public function process(\GearmanJob $job) {
		$this->workload = json_decode($job->workload());
		$this->logger->debug('Workload: ' . $job->workload());
		$this->logger->info('Task accepted. ID: ' . $this->id);

		if (!$this->workload) {
			$this->logger->warn('Workload is empty');
			return false;
		}
		return true;
	}
}