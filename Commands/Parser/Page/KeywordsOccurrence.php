<?php
namespace Commands\Parser\Page;

class KeywordsOccurrence extends \Command {
	private $word;

	public function __construct($word) {
		$this->word = $word;
		parent::__construct(null);
	}

	public function execute() {
		$parser = \Parsers\Page::instance($this->page);
		return $parser->keywordsOccurrence($this->word);
	}
}