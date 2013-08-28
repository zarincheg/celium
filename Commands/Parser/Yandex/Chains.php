<?php
namespace Commands\Parser\Yandex;
/**
 * Проверка наличия навигационных цепочек в выдаче яндекса. Кое-кто говорит, что их может не быть. но пока не доказано.
 * Если что, поменять проверку всего топа. а не только первой позиции
 */
class Chains extends \Command {
	private $domain = null;

	public function __construct($domain, $page) {
		$this->domain = $domain;
		parent::__construct($page);
	}

	public function execute() {
		$document = new \DOMDocument();
		@$document->loadHTML($this->page);
		$xpath = new \DOMXPath($document);
		$list = $xpath->query("//div[@class='b-body-items']/ol/li[1]/div[@class='b-serp-item__links']/span[1]/span[1]/a");
		
		if($list->length > 0)
			return true;
		else
			return false;
	}
}