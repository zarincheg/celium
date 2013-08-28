<?php
namespace Commands\Converter;

class Miralab extends \Command {
	protected $data;
	protected $domain;
	private $mongo;

	public function __construct($data, $domain) {
		$this->data = json_decode($data, true);
		$this->domain = $domain;
		$this->mongo = new \Mongo(\Configure::$get->database->mongodb);
	}

	public function execute() {
		$result = $this->mongo->newaudits->domains->findOne(['name' => $this->domain]);
		$result['yandex'] = array_merge($result['yandex'], $this->data);

		$this->mongo->newaudits->domains->update(['name' => $this->domain],
			['$set' => ['yandex' => $result['yandex']]],
			['upsert' => true]);
	}
}
