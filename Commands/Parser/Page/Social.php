<?php
namespace Commands\Parser\Page;

class Social extends \Command {
	public function execute() {
		$parser = \Parsers\Page::instance($this->page);
		return $parser->social();
	}
}