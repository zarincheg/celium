<?php
namespace Commands\Parser\Page;

class Advert extends \Command {
	public function execute() {
		$parser = \Parsers\Page::instance($this->page);
		return $parser->advert();
	}
}