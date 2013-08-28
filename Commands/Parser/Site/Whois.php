<?php
namespace Commands\Parser\Site;

class Whois extends \Command {
	private $domain;

	public function __construct($domain) {
		$this->domain = $domain;
	}

	private function strtotime($zone, $date) {
		$time = null;

		switch($zone) {
			case 'ru':
				if(preg_match('/^([0-9]{4})\.([0-9]{2})\.([0-9]{2})$/', $date, $m))
					$time = mktime(0, 0, 0, $m[2], $m[3], $m[1]);
				break;
			case 'ua':
				if(preg_match('!0-UANIC ([0-9]{4})([0-9]{2})([0-9]{2}).*?!', $date, $m)) {
					$time = mktime(0, 0, 0, $m[2], $m[3], $m[1]);
				}
				break;
			default:
				$time = strtotime($date);
		}

		return $time;
	}
	
	public function execute() {
		$result = array();
		$host = str_replace('www.', '', $this->domain);
		$zone = (preg_match('!.*\.([^\.]+)!', $host, $m) ? $m[1] : '');

		$whoisData = shell_exec('whois -- ' . escapeshellarg($host));

		if(!$whoisData)
			return false;

		if(preg_match('/\s*(?:created|Creation Date|Created On):\s*(.*)$/mi', $whoisData, $m)) {
			$regTime = $this->strtotime($zone, $m[1]);

			if($regTime !== NULL && $regTime <= time()) {
				$dt1 = new \DateTime();
				$dt2 = new \DateTime('@'.$regTime);
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
}