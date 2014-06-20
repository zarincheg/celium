<?php
namespace Celium\Services;
/**
 * Base Manager for using in Celium application
 * @author Kirill Zorin aka Zarin <zarincheg@gmail.com>
 *
 */

use Celium\DefaultLogger;
use Monolog\Logger;

class Manager extends \GearmanClient {
	protected $function;
	protected $logger;

	public function __construct($function, Logger $logger = null) {
		$this->function = $function;

		if (!$logger) {
			$this->logger = new DefaultLogger('client');
		} else {
			$this->logger = $logger;
		}

		parent::__construct();
	}

	/**
	 * Запускает клиент. Возвращает false в случае некорректных параметров.
	 * @param string $server Адрес job-сервера
	 * @param int $delay Задержка выполнения
	 * @return boolean
	 */
	public function start($server = '127.0.0.1:4730', $delay = 1) {
		if(is_array($server)) {
			$this->addServers(implode(',', $server));
		} elseif(is_string($server)) {
			$h = explode(':', $server);
			$this->addServer($h[0], $h[1]);
		} else {
			return false;
		}

		$this->setCompleteCallback(array($this, 'complete'));

		$this->logger->info('Node manager starting', [
			'server' => $server,
			'binding' => $this->function
		]);

		while(true) {
			if(!$this->process())
				break;

			usleep($delay);
		}

		return true;
	}

	public function complete(\GearmanTask $task) {
		return true;
	}

	/**
	 * Метод, который содержит основную бизнес-логику клиента. По умолчанию просто запускает все задачи.
	 * Если возвращает false, то работа клиента прерывается (!)
	 * @return boolean 
	 */
	public function process() {
		parent::runTasks();
		return true;
	}
}

?>
