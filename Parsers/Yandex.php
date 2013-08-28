<?php

namespace Parsers;

class Yandex {

	private function captcha($body) {
		if(!preg_match('!<form action="http://yandex.ru/checkcaptcha" method="GET">(.*?)</form>!is', $body, $form) || !preg_match('!<img src="(http://yandex\.ru/captchaimg.*?)"!is', $body, $captcha_url))
			return $body;

		$fields = array();
		preg_match_all('!<input.*?>!is', $form[1], $m);

		foreach($m[0] as $i) {
			if(!preg_match('!name.*?=.*?"(.*?)"!is', $i, $name))
				continue;

			if(!preg_match('!value.*?=.*?"(.*?)"!is', $i, $value))
				$value[1] = '';

			$fields[trim($name[1])] = trim($value[1]);
		}

		// captcha
		$fields['rep'] = \API\Antigate::recognize($captcha_url[1]);

		$content = file_get_contents('http://yandex.ru/checkcaptcha?' . http_build_query($fields));
		return $this->captcha($content);
	}

	public function searchInXml($query, $region = 213, $numdoc = 50, $numpage = false) {
		$yaXML = new \API\YandexXML();
		$params['count'] = $numdoc;

		if($numpage)
			$params['page'] = $numpage;

		$http = new \HttpRequest(sprintf('%s&lr=%d', \Configure::$get->api->yaxml, $region), \HttpRequest::METH_POST);
		$http->setBody($yaXML->buildQuery($query, $params));
		$http->send();
		$data = $http->getResponseBody();

		if(preg_match('!<response.*?><error code=.*?>(.*?)</error></response>!is', $data, $m))
			throw new \Exception($m[1]);

		if(!$data)
			throw new \Exception('No data');

		return $data;
	}

	public function spellcheck($url) {
		$http = new \HttpRequest(sprintf('http://wbms.yandex.net/spell_check_url.xml?checkurl=%s', $url));
		$http->setOptions(array('referer' => 'http://webmaster.yandex.ru/spellcheck.xml'));
		$http->send();
		$result = $http->getResponseBody();

		if(!$result)
			throw new \Exception('Bad spellcheck response (from Yandex)');

		$body = iconv('windows-1251', 'utf-8//IGNORE', $result);
		preg_match_all('!<span style="FONT-SIZE: 100%; COLOR: #000000; BACKGROUND-COLOR: #ffff00">(.*?)</span>!is', $body, $m);

		return array('words' => array_unique($m[1]), 'count' => count($m[1]));
	}

	/**
	 * @todo Оптимизировать поиск позиции
	 * @param $keyword
	 * @param $url
	 * @param $region
	 *
	 * @return bool|int
	 */
	public function position($keyword, $url, $region) {
		$yandexml = new \API\YandexXML();

		$try = 0;
		$numpage = 0;
		$numdocs = 50;
		$position = false;

		while($numpage < 20) {
			try {
				$xml = $this->searchInXml($keyword, $region, $numdocs, $numpage);
				$yandexml->loadXML($xml);
				$pos = $yandexml->getPositionUrl($url);

				if($pos) {
					$position = ($numpage * $numdocs) + $pos;
					break;
				}

				$try = 0;
				$numpage++;
			} catch(\Exception $e) {
				if($try < 3) {
					$try++;
					continue;
				}
			}
		}

		return $position;
	}

	/**
	 * Ищет снипеты в выдаче
	 * @todo Переписать на DOM
	 * @param $keyword
	 * @param $url
	 * @param $region
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function snippet($body, $url) {

		if(!preg_match('!<div class="b-serp-item__wrapper">.*?<a class="b-serp-item__title-link" href="'.$url.'".*?<div class="b-serp-item__text">(.*?)</div><div class="b-serp-item__links">!i', $body, $m))
			return array();

		$result['break'] = (strpos($m[1], '&hellip;') === false ? false : true);
		$m = html_entity_decode(strip_tags($m[1]));
		$result['length'] = mb_strlen($m);
		$result['uppercase'] = (bool)preg_match('![А-ЯA-Z]!', $m);

		return $result;
	}

	/**
	 * @todo Переделать парсинг, тут пиздец какой-то и с ним и с логикой
	 * @param $body Страница результатов поиска по сайту и ключевому слову
	 * @param $domain
	 *
	 * @return bool
	 */
	public function chains($body, $domain) {
		$chain = false;

		preg_match_all('!<div class="b-serp-item__links">(.*?)</div>!is', $body, $items);

		foreach($items[1] as $item) {
			preg_match_all('!<a class="b-serp-url__link" href="(.*?)".*?>(.*?)</a>!is', $item, $m);

			$chain = false;

			for($i = 1; $i < count($m[1]); ++$i) {
				$pos = mb_strpos($m[1][$i], $domain);

				if($pos === false)
					continue;

				$url = mb_substr(urldecode($m[1][$i]), $pos + mb_strlen($domain) + 1);
				$_chain = preg_replace(array('!<.*?>!is', '!….*!is'), '', $m[2][$i]);

				if($_chain == mb_substr($url, 0, mb_strlen($_chain))) {
					// навигационная цепочка не найдена => считаем что её нет во всем item'е
					$chain = false;
					break;
				}

				$chain = true;
			}

			if($chain)
				break;
		}

		return $chain;
	}

	/**
	 * Пробует определить регион в поисковой выдаче. В случае успеха возвращает название и ID региона
	 * @param $body Страница с поисковой выдачей по запросу поиска внутри сайта
	 *
	 * @return array|bool
	 */
	public function region($body) {
		$region = false;

		preg_match_all('!<div class="b-serp-item__links">(.*?)</div>!is', $body, $items);

		foreach($items[1] as $item) {
			if(preg_match('!<a class="b-serp-url__link b-link b-link_ajax_yes" .*?>.*?</a>!is', $item, $m)) {
				$region = strip_tags($m[0]);
				break;
			}
		}

		if($region) {
			return array('region' => $region, 'region_id' => \Api\Services\Metrix::getRegionYandexid($region));
		} else {
			return false;
		}
	}

	/**
	 * @param $body Страница с органической выдачей по домену и региону
	 * @param $domain
	 *
	 * @return array
	 */
	public function fastLinks($body, $domain) {
		$fast_links = array('urls' => array(), 'anchors' => array());

		preg_match_all('!<li class="b-serp-item( b-serp-item_glue_site|)">(.*?)</div></li>!is', $body, $items);

		foreach($items[2] as $item) {
			$url = '';
			if(preg_match('!<a class="b-serp-item__title-link" href="(.*?)".*?>!is', $item, $m))
				$url = $m[1];

			if(parse_url($url, PHP_URL_HOST) != $domain)
				continue;

			preg_match_all('!<a class="b-serp-sitelinks__sitelinks-link b-link".*?href="(.*?)".*?>(.*?)</a>!is', $item, $m);
			$fast_links = array('urls' => $m[1], 'anchors' => $m[2]);
			break;
		}

		return $fast_links;
	}
}
