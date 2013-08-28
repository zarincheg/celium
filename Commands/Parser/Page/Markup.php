<?php
namespace Commands\Parser\Page;

class Markup extends \Command {
	public function execute() {
		//$parser = new \Parsers\Page($this->page);
		$parser = \Parsers\Page::instance($this->page);
		return $parser->markup();
	}
}