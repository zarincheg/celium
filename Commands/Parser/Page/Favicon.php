<?php
namespace Commands\Parser\Page;

class Favicon extends \Command {
	private $domain = null;

	public function __construct($domain, $page) {
		$this->domain = $domain;
		parent::__construct($page);
	}

	public function execute() {
		$parser = \Parsers\Page::instance($this->page);
		return $parser->favicon($this->domain);
	}
}