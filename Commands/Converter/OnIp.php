<?php
namespace Commands\Converter;

class OnIp extends \Command {
	protected $data;
	protected $url;
	protected $keyword;
	protected $domain;
	private $mongo;

	public function __construct($data, $url, $keyword, $domain) {
		$this->data = $data;
		$this->domain = $domain;
		$this->mongo = new \Mongo(\Configure::$get->database->mongodb);
		parent::__construct(null);
	}

	public function execute() {
		$doc = ['domainsOnIP' => $this->data['site_onip']];
		$this->mongo->newaudits->domains->update(['name' => $this->domain],
												 ['$set' => $doc],
												 ['upsert' => true]);
	}
}
