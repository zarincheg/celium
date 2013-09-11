<?php
/**
 * Class for manage celeum-workers and celium-managers and start them with CLI
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

namespace Celium;


class Starter {
	private static $managers = [];
	private static $workers = [];

	// starter.php --node facebook_collector --worker
	public static function onStartWorker($nodeName, callable $function) {
		self::$workers[$nodeName] = $function;
	}

	public static function onStartManager($nodeName, callable $function) {
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
			$worker = $function();

			if(!($worker instanceof \Celium\Services\Worker))
				throw new \Exception('onStartManager function must return the object of \Celium\Services\Worker');

			$worker->start();
		} elseif(isset($params['manager'])) {
			$logger->info('Manager is starting...');

			if(!isset(self::$managers[$params['node']]))
				throw new \Exception('Managers for '.$params['node'].' not found');

			$function = self::$managers[$params['node']];
			$manager = $function();

			if(!($manager instanceof \Celium\Services\Manager))
				throw new \Exception('onStartManager function must return the object of \Celium\Services\Manager');

			$manager->start();
		} else {
			throw new \Exception("Nothing to start. Use --worker or --manager options.\n");
		}
	}
}