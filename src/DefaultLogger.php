<?php
/**
 * Default logger based on Monolog
 * @author Zorin Kirill <zarincheg@gmail.com>
 */

namespace Celium;

use Monolog\Logger;
use Monolog\Handler\MongoDBHandler;
use Monolog\Handler\StreamHandler;

class DefaultLogger extends Logger {
	public function __construct($name) {
		$mongo = new \MongoClient(Config::$get->database->mongodb);

		parent::__construct($name, [
			new StreamHandler(Config::$get->logging->file),
			new MongoDBHandler($mongo, Config::$get->logging->mongodb->database, Config::$get->logging->mongodb->collection)
		]);
	}
} 