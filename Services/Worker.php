<?php
namespace Services;
/**
 * Description of Worker
 * 
 * @author Kirill Zorin <zarincheg@gmail.com>
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
		
		$this->logger->info('Node worker starting. Server: '.$server.'. Binding: '.$this->function);

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
		$this->logger->debug('Workload: ' . $job->workload());
		$this->logger->info('Task accepted');

		if (!$this->workload) {
			$this->logger->warn('Workload is empty');
			return false;
		}
		return true;
	}
}