<?php
namespace Celium;
/**
 * Базовый класс для создания комманд. Все комманды должны возвращать строку, а лучше json
 *
 * @todo унифицировать конструктор
 * @author Kirill Zorin aka Zarin <zarincheg@gmail.com>
 * @copyright Copyright (c) 2011, Kirill Zorin, SeoStopol LLC
 * 
 */
abstract class Command {
	protected $env;
	protected $page;
	protected $logger;

	public function __construct($page = null) {
		$this->page = $page;
		$this->logger = \Logger::getRootLogger();
	}

	abstract public function execute($input = null);
	
	public function env(array $e) {
		$this->env = $e;
	}
}

?>
