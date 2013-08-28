<?php
namespace Commands\Parser\Site;

class Webarchive extends \Command {
	public function execute() {
		$result = array();

		if(preg_match('!<strong>.*?<a href=\".*?\">(.*?)</a>!', $this->page, $m))
			$result['age'] = floor((time() - strtotime($m[1])) / (3600 * 24 * 30));

		if(preg_match('!<strong>.*?<a href=\"(.*?)\">.*?</a>!', $this->page, $m))
			$result['link'] = $m[1];

		return $result;
	}
}