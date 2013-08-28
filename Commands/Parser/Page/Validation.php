<?php
namespace Commands\Parser\Page;

class Validation extends \Command {
	private $url = null;

	public function __construct($url, $page) {
		$this->url = $url;
		parent::__construct($page);
	}

	public function execute() {
		$parser = \Parsers\Page::instance($this->page);
		return $parser->validation($this->url);
	}
}