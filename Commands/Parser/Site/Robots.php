<?php
namespace Commands\Parser\Site;

class Robots extends \Command { 
	public function execute() {
		$robots = array();

		preg_match_all('!disallow!i', $this->page, $m);
		$robots['disallow'] = count($m[0]);

		if(preg_match('!host[\s]*:(.*)!i', $this->page, $m))
			$robots['host'] = mb_strtolower(trim($m[1]), 'UTF-8');

		if(preg_match('!sitemap[\s]*:(.*)!i', $this->page, $m))
			$robots['sitemap'] = trim($m[1]);

		preg_match_all('!User-agent[\s]*:(.*)!i', $this->page, $m);
		$robots['user_agents'] = array_map(function ($item) {
			return trim($item);
		}, $m[1]);

		$robots['lines_count'] = mb_substr_count($this->page, PHP_EOL);

		return $robots;
	}
}