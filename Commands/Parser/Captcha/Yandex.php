<?php
namespace Commands\Parser\Captcha;

class Yandex extends \Command {
	public function execute() {
		$document = new \DOMDocument();
		@$document->loadHTML($this->page);
		$xpath = new \DOMXPath($document);

		$list = $xpath->query("//img[@class='b-captcha__image']");
		
		if(!$list->length)
			return null;

		$img = $list->item(0)->attributes->getNamedItem('src')->nodeValue;
		$inputs = $xpath->query("//form/input");
		$fields = [];
		
		for($i = 0; $i < $inputs->length; $i++) {
			$attr = $inputs->item($i)->attributes;
			$fields[$attr->getNamedItem('name')->nodeValue] = $attr->getNamedItem('value')->nodeValue;
		}
		
		return ['img' => $img, 'inputs' => $fields];
	}
}