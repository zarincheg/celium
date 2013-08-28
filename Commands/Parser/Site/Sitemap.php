<?php
namespace Commands\Parser\Site;

class Sitemap extends \Command {
	protected $domain = null;
	protected $sitemap = null;

	public function __construct($domain, $sitemap) {
		$this->domain = $domain;
		$this->sitemap = $sitemap;
	}

	public function execute() {
		$result = array();
		$result['url'] = 'http://'.$this->domain.'/sitemap.xml';

		http_get($result['url'], ['redirect' => 3], $info); //@todo Убрать этот костыль, брать инфу из fetch storage

		$result['found'] = $info['response_code'] == 200 ? true : false;
		$xml = new \XMLReader();
		$xml->xml($this->sitemap);
		$xml->setParserProperty(\XMLReader::VALIDATE, true);
		$result['valid'] = $xml->isValid();

		preg_match_all('!<loc>(.*?)</loc>!is', $this->sitemap, $m);
		$result['urls_count'] = count($m[1]);

		// in monthes
		if(preg_match('!<lastmod>(.*?)</lastmod>!is', $this->sitemap, $m)) {
			$now = new \DateTime();
			$interval = $now->diff(new \DateTime($m[1]));
			$result['modified'] = (int)$interval->format('%y') * 12 + (int)$interval->format('%m');
		}

		return $result;
	}
}