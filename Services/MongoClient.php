<?php
/**
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

namespace Services;


class MongoClient extends Manager {
	protected $db = null;

	public function __construct($function) {
		$this->db = new \Mongo(\Configure::$get->database->mongodb);
		parent::__construct($function);
	}
}