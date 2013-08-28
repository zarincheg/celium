<?php
namespace Commands\Parser\Page;

class Meta extends \Command {
	public function execute() {
		$parser = \Parsers\Page::instance($this->page);
		return $parser->meta();
	}
}