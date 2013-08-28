<?php

namespace Parsers;

class Site {

	private function strtotime($zone, $date) {
		/*
		 * формат может быть в разным в зависимости от доменной зоны,
		 * например, для .ru это
		 * 	created:    2008.05.12
		 * для .net .com
		 * 	Creation Date: 04-oct-2001
		 * для .org
		 * 	Created On:02-May-2001 15:53:38 UTC
		 * для .ru
		 *  created:       2007.08.14
		 * для .ua
		 *  created:    0-UANIC 20100216154947
		 */
		$time = null;
		switch($zone) {
			case 'ru':
				if(preg_match('/^([0-9]{4})\.([0-9]{2})\.([0-9]{2})$/', $date, $m))
					$time = mktime(0, 0, 0, $m[2], $m[3], $m[1]);
				break;
			case 'ua':
				if(preg_match('!0-UANIC ([0-9]{4})([0-9]{2})([0-9]{2}).*?!', $date, $m))
					$time = mktime(0, 0, 0, $m[2], $m[3], $m[1]);
				break;
			default:
				$time = strtotime($date);
		}

		return $time;
	}

	public function whois($domain) {
		$result = array();
		$host = str_replace('www.', '', $domain);
		$zone = (preg_match('!.*\.([^\.]+)!', $host, $m) ? $m[1] : '');

		$whoisData = shell_exec('whois -- ' . escapeshellarg($host));

		if(!$whoisData)
			return false;

		if(preg_match('/\s*(?:created|Creation Date|Created On):\s*(.*)$/mi', $whoisData, $m)) {
			$regTime = $this->strtotime($zone, $m[1]);

			if($regTime <= time()) {
				$dt1 = new \DateTime();
				$dt2 = new \DateTime('@' . $regTime);
				$interval = $dt1->diff($dt2);
				$result['age'] = (int)$interval->format('%y') * 12 + (int)$interval->format('%m');
				$result['registration'] = $dt2->format('m.d.y');
			}
		}

		if(preg_match('/\s*(?:paid-till|Expiration Date):\s*(.*)$/mi', $whoisData, $m)) {
			$expireTime = $this->strtotime($zone, $m[1]);

			if($expireTime)
				$result['expiration'] = date('m.d.y', $expireTime);
		}

		if(preg_match('!(state|Status):(.*)!', $whoisData, $m))
			$result['verification'] = explode(',', preg_replace('!\s+!', '', $m[2]));

		return $result;
	}

	public function webarchive($webarchivePage) {
		$result = array();

		if(preg_match('!<strong>.*?<a href=\".*?\">(.*?)</a>!', $webarchivePage, $m)) {
			$result['age'] = floor((time() - strtotime($m[1])) / (3600 * 24 * 30));
		}

		if(preg_match('!<strong>.*?<a href=\"(.*?)\">.*?</a>!', $webarchivePage, $m)) {
			$result['link'] = $m[1];
		}

		return $result;
	}

	public function domainsOnIP($bingPage) {
		if(preg_match('!<span class="sb_count" id="count">.*?([0-9\.,]+)\s.*?</span>!is', $bingPage, $m))
			return(int)str_replace(array(',', '.'), '', $m[1]);

		return false;
	}

	/**
	 * @param $content Содержимое файла robots.txt
	 *
	 * @return array Информация о файле robots.txt
	 */
	public function robots($content) {
		$robots = array();

		preg_match_all('!disallow!i', $content, $m);
		$robots['disallow'] = count($m[0]);

		if(preg_match('!host[\s]*:(.*)!i', $content, $m))
			$robots['host'] = mb_strtolower(trim($m[1]), 'UTF-8');

		if(preg_match('!sitemap[\s]*:(.*)!i', $content, $m))
			$robots['sitemap'] = trim($m[1]);

		preg_match_all('!User-agent[\s]*:(.*)!i', $content, $m);
		$robots['user_agents'] = array_map(function ($item) {
			return trim($item);
		}, $m[1]);

		$robots['lines_count'] = mb_substr_count($content, PHP_EOL);

		return $robots;
	}

	/**
	 * @param $domain Домен
	 * @param $robots Содержимое файла robots.txt
	 *
	 * @return array Информация о карте сайта
	 */
	public function sitemap($domain, $robots) {
		$result = array();
		$result['url'] = sprintf('http://%s/sitemap.xml', $domain);
		if(preg_match('!sitemap:[\s]+(.*)!i', $robots, $m))
			$result['url'] = $m[1];

		$http = new \HttpRequest($result['url']);
		$http->send();
		$sitemap = $http->getResponseBody();

		if($sitemap && $http->getResponseCode() == 200) {
			$result['found'] = true;
			$xml = new \XMLReader();
			$xml->xml($sitemap);
			$xml->setParserProperty(\XMLReader::VALIDATE, true);
			$result['valid'] = $xml->isValid();

			preg_match_all('!<loc>(.*?)</loc>!is', $sitemap, $m);
			$result['urls_count'] = count($m[1]);

			// in monthes
			if(preg_match('!<lastmod>(.*?)</lastmod>!is', $sitemap, $m)) {
				$now = new \DateTime();
				$interval = $now->diff(new \DateTime($m[1]));
				$result['modified'] = (int)$interval->format('%y') * 12 + (int)$interval->format('%m');
			}
		}

		return $result;
	}

	/**
	 * @param $optixPage
	 *
	 * @return array Кол-во ссылающихся страниц и доменов по данным сайта optix.ru
	 */
	public function incoming($optixPage) {
		$incoming = array();

		if(preg_match('!Кол-во ссылающихся доменов:\s?(\d+)!', $optixPage, $m))
			$incoming['domains'] = (int)$m[1];

		if(preg_match('!Кол-во ссылающихся страниц:\s?(\d+)!', $optixPage, $m))
			$incoming['pages'] = (int)$m[1];

		return $incoming;
	}

	/**
	 * @param $yadroPage Содержимое страницы статистики counter.yadro.ru
	 *
	 * @return array Информация о кол-ве посетителей и просмотров
	 */
	public function visitors($yadroPage) {
		$result = array();

		if(preg_match('!LI_month_hit = (\d*);!i', $yadroPage, $hit))
			$result['hit'] = $hit[1];
		if(preg_match('!LI_month_vis = (\d*);!i', $yadroPage, $vis))
			$result['vis'] = $vis[1];

		return $result;
	}

	/**
	 * @param $domain Домен
	 * @param $dmozPage Страница с результатом поиска по dmoz.org
	 *
	 * @return bool|string Наличие в каталоге Dmoz и название категории
	 */
	public function dmoz($domain, $dmozPage) {
		if(preg_match(sprintf('!-- http://%s[/]?&nbsp;&nbsp;&nbsp;&nbsp;.*?href=".*?/([^/]*)/"!is', preg_quote($domain)), $dmozPage, $m))
			return urldecode($m[1]);

		return false;
	}

	// есть редирект www.35cm -> 35cm
	// 35cm -> www.35cm
	// нет редиректа с 35cm на www.35cm
	public function redirect($domain) {
		$anotherMirror = (substr($domain, 0, 4) == 'www.' ? substr($domain, 4) : 'www.' . $domain);
		$http = new \HttpRequest($anotherMirror);
		$http->send();
		$url = $http->getResponseInfo('effective_url');

		if($http->getResponseInfo('redirect_count') > 0 && parse_url($url, PHP_URL_HOST) != $anotherMirror)
			return array('hasRedirect' => true, 'from' => $anotherMirror, 'to' => parse_url($url, PHP_URL_HOST));
		else
			return array('hasRedirect' => true, 'from' => $domain, 'to' => parse_url($url, PHP_URL_HOST));
	}
}
