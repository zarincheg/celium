<?php
namespace Commands\Parser;

class App extends \Command {
	private $url = null;
	private $headers = null;

	public function __construct($url, $headers, $page) {
		$this->url = $url;
		$this->headers = $headers;
		parent::__construct($page);
	}

	public function execute() {
		return \Parsers\Wappalyzer::analyze($this->url, $this->page, $this->headers);
	}
}