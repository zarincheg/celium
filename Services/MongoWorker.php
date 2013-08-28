<?php

namespace Services;


class MongoWorker extends Worker {
	/**
	 * @var \Mongo|null
	 */
	protected $db = null;

	public function __construct($function) {
		$this->db = new \Mongo(\Configure::$get->database->mongodb);
		parent::__construct($function);
	}
}