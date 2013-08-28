<?php
namespace Commands\Parser\Yandex;

class Spellcheck extends \Command {
	public function __construct($url) {
		$this->url = $url;
		parent::__construct(null);
	}

	public function execute() {
		/*$http = new \HttpRequest(sprintf('http://wbms.yandex.net/spell_check_url.xml?checkurl=%s', $url));
		$http->setOptions(array('referer' => 'http://webmaster.yandex.ru/spellcheck.xml'));
		$http->send();
		$result = $http->getResponseBody();

		if(!$result)
			throw new \Exception('Bad spellcheck response (from Yandex)');

		$body = iconv('windows-1251', 'utf-8//IGNORE', $result);*/
		preg_match_all('!<span style="FONT-SIZE: 100%; COLOR: #000000; BACKGROUND-COLOR: #ffff00">(.*?)</span>!is', $body, $m);

		return array('words' => array_unique($m[1]), 'count' => count($m[1]));
	}
}