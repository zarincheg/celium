<?php
/**
 * Class for manage celeum-workers and celium-managers and start them with CLI
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

namespace Celium;


class Starter {
	private static $managers = [];
	private static $workers = [];

	/**
	 * Add worker for start it
	 * @param string $nodeName
	 * @param callable $function Function that must return the object of \Celium\Services\Worker class for start it. Can get two parameters: node name and cli args by second argument.
	 */
	public static function addWorker($nodeName, callable $function) {
		self::$workers[$nodeName] = $function;
	}

	/**
	 * Add manager for start it
	 * @param string $nodeName
	 * @param callable $function Function that must return the object of \Celium\Services\Manager class for start it. Can get two parameters: node name and cli args by second argument.
	 */
	public static function addManager($nodeName, callable $function) {
		self::$managers[$nodeName] = $function;
	}

	public static function start() {
		$logger = \Logger::getRootLogger();

		$longopts = [
			'node:',
			'worker',
			'manager'
		];

		$params = getopt("", $longopts);

		if(!isset($params['node']))
			throw new \Exception('Parameter --node is required. It must be a name of Celium Node.');

		if(isset($params['worker'])) {
			$logger->info('Worker is starting...');

			if(!isset(self::$workers[$params['node']]))
				throw new \Exception('Workers for '.$params['node'].' not found');

			$function = self::$workers[$params['node']];
			$worker = $function($params['node'], $params);

			if(!($worker instanceof \Celium\Services\Worker))
				throw new \Exception('onStartManager function must return the object of \Celium\Services\Worker');

			$worker->start();
		} elseif(isset($params['manager'])) {
			$logger->info('Manager is starting...');

			if(!isset(self::$managers[$params['node']]))
				throw new \Exception('Managers for '.$params['node'].' not found');

			$function = self::$managers[$params['node']];
			$manager = $function($params['node'], $params);

			if(!($manager instanceof \Celium\Services\Manager))
				throw new \Exception('onStartManager function must return the object of \Celium\Services\Manager');

			$manager->start('127.0.0.1:4730', 1000*1000); // @todo Make it configurable
		} else {
			throw new \Exception("Nothing to start. Use --worker or --manager options.\n");
		}
	}
}