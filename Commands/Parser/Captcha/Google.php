<?php
namespace Commands\Parser\Captcha;

class Google extends \Command {
	public function execute() {
		$document = new \DOMDocument();
		@$document->loadHTML($this->page);
		$xpath = new \DOMXPath($document);

		$form = $xpath->query("//form");
		
		if(!$form->item(0))
			return null;

		$isCaptcha = $form->item(0)->attributes->getNamedItem('action')->nodeValue;
		
		if($isCaptcha !== 'Captcha')
			return null;
		
		$list = $xpath->query("//img");
		
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