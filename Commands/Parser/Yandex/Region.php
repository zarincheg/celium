<?php
namespace Commands\Parser\Yandex;

class Region extends \Command {
	public function execute() {
		$document = new \DOMDocument();
		@$document->loadHTML($this->page);
		$xpath = new \DOMXPath($document);
		$list = $xpath->query("//div[@class='b-body-items']/ol/li[1]/div[@class='b-serp-item__links']/span[1]/span[2]/a");

		return ['region' => $list->item(0)->textContent, 'region_id' => 0];
	}
}