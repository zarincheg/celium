<?php
namespace Celium;
/**
 * Реестр комманд и алиасов к ним
 * @author Kirill Zorin <zarincheg@gmail.com>
 */
class CommandRegistry {
	static private $list = [];

	static public function get($command, $params = []) {
		if(!isset(self::$list[$command])) {
			$logger = \Logger::getRootLogger();
			$logger->fatal('Command not found: '.$command);
			throw new \Exception("Command not found");
		}

		$commandClass = self::$list[$command];

		$class = new \ReflectionClass($commandClass);

		if($params)
			return $class->newInstanceArgs($params);
		else
			return $class->newInstance();
	}

	static public function add($alias, $class) {
		self::$list[$alias] = $class;
	}
}