<?php
namespace Commands\Converter;

class Robots extends \Command {
	protected $data;
	protected $domain;
	private $mongo;

	public function __construct($data, $url, $keyword, $domain) {
		$this->data = $data;
		$this->domain = $domain;
		$this->mongo = new \Mongo(\Configure::$get->database->mongodb);
		parent::__construct(null);
	}

	public function execute() {
		$doc = ['robots' => $this->data['site_robots']];
		$this->mongo->newaudits->domains->update(['name' => $this->domain],
			['$set' => $doc],
			['upsert' => true]);
	}
}