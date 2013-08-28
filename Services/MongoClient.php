<?php
/**
 * Created by JetBrains PhpStorm.
 * User: zarin
 * Date: 5/7/13
 * Time: 6:12 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Gearman;


class MongoClient extends Client {
	protected $db = null;

	public function __construct($function) {
		$this->db = new \Mongo(\Configure::$get->database->mongodb);
		parent::__construct($function);
	}
}