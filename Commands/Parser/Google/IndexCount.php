<?php
namespace Commands\Parser\Google;

class IndexCount extends \Command {
	public function execute() {
		$parser = \Parsers\Google::instance($this->page);
		return $parser->indexCount();
	}
}