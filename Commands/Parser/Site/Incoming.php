<?php
namespace Commands\Parser\Site;
/**
 * @todo Solomono сменили верстку, фиксить парсер
 */
class Incoming extends \Command {
	public function execute() {
		$incoming = [];

		if(preg_match('!Кол-во ссылающихся доменов:\s?(\d+)!', $this->page, $m))
			$incoming['domains'] = (int)$m[1];

		if(preg_match('!Кол-во ссылающихся страниц:\s?(\d+)!', $this->page, $m))
			$incoming['pages'] = (int)$m[1];

		return $incoming;
	}
}