<?php
namespace Commands\Parser\Yandex;

class Snippet extends \Command {
	public function execute() {
		$document = new \DOMDocument();
		@$document->loadHTML($this->page);
		$xpath = new \DOMXPath($document);
		$list = $xpath->query("//div[@class='b-body-items']/ol/li[1]/div[@class='b-serp-item__text']");
		$snippet = $list->item(0)->textContent;

		return ['text' => $snippet, 'length' => mb_strlen($snippet)];
	}
}