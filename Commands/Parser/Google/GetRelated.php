<?php
namespace Commands\Parser\Google;

class GetRelated extends \Command {
	public function execute() {
		$parser = \Parsers\Google::instance($this->page);
		return $parser->getRelated();
	}
}