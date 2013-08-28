<?php
namespace Commands\Parser\Yandex;

class FastLinks extends \Command {
	private $domain = null;

	public function __construct($domain, $page) {
		$this->domain = $domain;
		parent::__construct($page);
	}

	public function execute() {
		$fastLinks = array('urls' => array(), 'anchors' => array());
		$document = new \DOMDocument();
		@$document->loadHTML($this->page);
		$xpath = new \DOMXPath($document);
		$list = $xpath->query("//div[@class='b-body-items']/ol/li[1]/div[@class='b-serp-sitelinks']/a");

		for($i = 0; $i < $list->length; $i++) {
			$item = $list->item($i);
			$fastLinks['anchors'][] = $item->textContent;
			$fastLinks['urls'][] = $item->attributes->getNamedItem('href')->nodeValue;
		}

		return $fastLinks;
	}
}