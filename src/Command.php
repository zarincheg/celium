<?php
namespace Celium;
/**
 * Базовый класс для создания комманд. Все комманды должны возвращать строку, а лучше json
 *
 * @todo унифицировать конструктор
 * @author Kirill Zorin aka Zarin <zarincheg@gmail.com>
 * @copyright Copyright (c) 2011, Kirill Zorin
 * 
 */

use Monolog\Logger;

abstract class Command {
	protected $env;
	protected $logger;

	public function __construct() {
		$this->logger = new DefaultLogger('command');
	}

	public function setLogger(Logger $logger) {
		$this->logger = $logger;
	}

	abstract public function execute($input = null);
	
	public function env(array $e) {
		$this->env = $e;
	}
}

?>
