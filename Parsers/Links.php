<?php
namespace Parsers;
/**
 * @fixme self::relative incorrect detect for domain
 * 
 * @author Kirill Zorin
 * 
 */
class Links {
	private $broken = array();
	
	public function get($html, $address = '') {
		$address = $this->address($address);
		if(!$address)
			return false;
		
		preg_match_all('!<a\s[^>]*?href=[\'"]?([^\'"\s]+).*?>(.*?)</a>!i', $html, $matches);
		$links = array();
		
		for($i = count($matches[1]); $i--;) {
			$link = array('url' => $matches[1][$i],
						  'anchor' => $matches[2][$i]);
			$link['absolute'] = $this->absolute($link['url'], $address['base']);
			
			if(filter_var($link['absolute'], FILTER_VALIDATE_URL) === false) {
				$this->broken[] = $link['url'];
				continue;
			}
			
			$link['absolute'] = $this->hash($link['absolute']);
			
			$link['external'] = $this->external($link['absolute'], $address['domain']);
			
			if(!$link['external'])
				$link['relative'] = $this->relative($link['url'], $address['base']);
			
			$links[] = $link;
		}
		
		return $links;
	}
	
	public function absolute($link, $base = '') {
		if ($link{0} == '/') {
			return preg_replace('!^(\w+://[^/]+).*$!', '$1', $base) . $link;
		}
		if(stripos($link, './') === 0) {
			$link = substr($link, 2);
		}
		if (preg_match('!^\w+://!', $link)) {
			return $link;
		}
		return preg_replace('!^(\w+://(www\.)?(?:[^/?]+/)+).*$!', '$1', $base) . $link;
	}
	
	public function external($link, $host) {
		$linkHost = parse_url($link, PHP_URL_HOST);

		if($linkHost != $host)
			return true;
		else
			return false;
	}
	
	public function relative($link, $base) {
		if(stripos($link, $base) === 0)
			return false;
		else
			return true;
	}
	
	public function broken() {
		return $this->broken;
	}
	
	public function hash($link) {
		$h = stripos($link, '#');

		if(!$h)
			return $link;

		return substr($link, 0, $h);
	}
	
	public function address($url) {
		if(!preg_match('!http://[a-z0-9\-\.]+\.[a-z]{2,4}/?$!', $url))
			$url = 'http://'.$url;

		$url = parse_url($url);
		
		if(!$url)
			return false;
		
		$url['path'] = isset($url['path']) ? $url['path'] : '/';
		$base = $url['scheme'].'://'.$url['host'].$url['path'];
		$domain = $url['host'];
		
		return array('base' => $base, 'domain' => $domain);
	}
}