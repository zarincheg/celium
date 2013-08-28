<?php
namespace Commands\Parser\Google;

class Pr extends \Command {
	private $url;

	public function __construct($url) {
		$this->url = $url;
		parent::__construct(null);
	}

	public function execute() {
		$parser = \Parsers\Google::instance($this->page);
		return $parser->pr($this->url);
	}
}