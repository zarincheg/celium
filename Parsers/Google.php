<?php

namespace Parsers;

/**
 * Анализ поисковой выдачи Google
 * url: http://www.google.com/search?q=urlencode($query)&bih=$region&hl=en
 */
class Google {
	private $page = null;
	static private $parser = null;
	public $document = null;

	public function __construct($page) {
		$this->page = $page;
		$this->document = new \DOMDocument();
		@$this->document->loadHTML($page);
	}

	public function setPage($page) {
		$this->page = $page;
	}

	public function setDocument(\DOMDocument $document) {
		$this->document = $document;
	}

	private function fetch($q, $fields=array()) {
		$q .= ($fields?(mb_strpos($q,'?') === false?'?':'') . http_build_query($fields):'');
		$body = file_get_contents($q);
		if(preg_match('!<title>Sorry...</title>!',$body))
			throw new \Exception('Google sorry');
		
		return $this->captcha($body);
	}

	/**
	 * @todo Обработку капчей тоже вынести куда то в fetch компонент
	 * @param $body
	 *
	 * @return mixed
	 */
	public function captcha($body) {
		if(!preg_match('!<form action="Captcha" method="get">(.*?)</form>!is', $body, $form) || !preg_match('!<img src="(/sorry/image\?id=.*?)".*?>!is', $body, $m)) {
			// captcha handled, follow body onload redirect
			if(preg_match('!<BODY onLoad="location\.replace\(\'(.*?)\'\+document\.location\.hash\)">.*?</BODY></HTML>!is', $body, $redirect))
				return $this->fetch(html_entity_decode(urldecode(str_replace('\x', '%', $redirect[1])), \ENT_QUOTES, 'UTF-8'));
			
			return $body;
		}

		$base = new \Url('http://www.google.com/');
		$captcha_url = new \Url($m[1]);
		if($captcha_url->isRelative())
			$captcha_url = $base->resolved($captcha_url);

		$fields = array();
		preg_match_all('!<input.*?>!is', $form[1], $m);

		foreach($m[0] as $i) {
			if(!preg_match('!name.*?=.*?"(.*?)"!is', $i, $name))
				continue;

			if(!preg_match('!value.*?=.*?"(.*?)"!is',$i,$value))
				$value[1] = '';

			$fields[trim($name[1])] = trim($value[1]);
		}

		$fields['continue'] = urldecode($fields['continue']);
		$fields['captcha'] = \API\Antigate::recognize($captcha_url->toString());

		return $this->captcha($this->fetch('http://www.google.com/sorry/Captcha', $fields));
	}

	static public function instance($page) {
		if(self::$parser === null) {
			self::$parser = new Google($page);
		}

		if(self::$parser instanceof Google) {
			self::$parser->setPage($page);
			self::$parser->setDocument(new \DOMDocument());
			@self::$parser->document->loadHTML($page);
			return self::$parser;
		} else {
			return false;
		}
	}

	public function indexCount() {
		if($this->document->getElementById('resultStats')) {
			$text = $this->document->getElementById('resultStats')->nodeValue;
			preg_match('![0-9]+\s?[0-9]+!iu', $text, $m);
			return (int)preg_replace('!\s+!ui', '', $m[0]);
		}

		return false;
	}

	public function getRelated() {
		$related = [];

		if(!$this->document->getElementsByTagName('cite')->length)
			return false;

		/** @var $el \DOMNode */
		foreach($this->document->getElementsByTagName('cite') as $el) {
			$related['top'][] = $el->nodeValue;
		}

		$related['count'] = $this->indexCount();
		return $related;
	}

	static public function pr($url) {
		if(!preg_match('!^http://!', $url))
			$url = 'http://'.$url;

		$url = 'http://toolbarqueries.google.com/tbr?client=navclient-auto&ch='.\GHash::get($url).'&features=Rank&q=info:'.$url.'&num=100&filter=0';

		$http = new \HttpRequest();
		//$http->setOptions(array('proxyhost' => '178.218.208.118:8999'));
		$http->setUrl($url);
		$http->send();

		return (int)trim(preg_replace('!Rank_.*?:.*?:!', '', $http->getResponseBody()));
	}

	/**
	 * Возвращает позицию сайта в выдаче Google
	 * @todo Задача предполагает анализ нескольких страниц. Подумать как это сделать в рамках нового парсинга
	 * @param $body array Набор страниц с выдачей
	 * @param $url
	 *
	 * @return array
	 */
	static public function position(array $body, $url) {
		$document = new \DOMDocument();
		$nodes = [];
		$position = false;

		for($i = 0; $i < count($body); $i++) {
			@$document->loadHTML($body[$i]);
			$xpath = new \DOMXPath($document);
			$list = $xpath->query("//h3[@class='r']/a");

			foreach($list as $node) {
				$nodes[] = $node;
			}
		}

		/** @var $nodes \DOMNodeList */
		for($i = 0; $i < count($nodes); $i++) {
			if(stripos($nodes[$i]->getAttribute('href'), parse_url($url, PHP_URL_HOST))) {
				$i++;
				$position = $i;
				break;
			}
		}

		return $position;
	}
}
