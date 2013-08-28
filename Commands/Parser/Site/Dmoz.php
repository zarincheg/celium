<?php
namespace Commands\Parser\Site;

class Dmoz extends \Command {
	protected $domain = null;
	protected $document = null;

	public function __construct($domain, $page) {
		$this->domain = $domain;
		$this->document = new \DOMDocument();
		@$this->document->loadHTML($page);
		parent::__construct($page);
	}

	public function execute() {
		$xpath = new \DOMXPath($this->document);
		$list = $xpath->query("//div[@class='ref']");

		$text = null;
		$category = null;

		foreach($list as $node) {
			$text = $node->nodeValue;
			$category = $node->childNodes->item(1)->nodeValue;

			if(preg_match('!'.$this->domain.'!ui', $text)) {
				$category = str_replace(': ', '/', $category);
				break;
			}
		}

		return $category;
	}
}