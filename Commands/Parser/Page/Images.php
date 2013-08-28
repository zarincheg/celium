<?php
namespace Commands\Parser\Page;

class Images extends \Command {
	public function execute() {
		$parser = \Parsers\Page::instance($this->page);
		return $parser->images();
	}
}