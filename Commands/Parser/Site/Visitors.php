<?php
namespace Commands\Parser\Site;

class Visitors extends \Command {
	public function execute() {
		$result = [];

		if(preg_match('!LI_month_hit = (\d*);!i', $this->page, $hit))
			$result['hit'] = $hit[1];
		if(preg_match('!LI_month_vis = (\d*);!i', $this->page, $vis))
			$result['vis'] = $vis[1];

		return $result;
	}
}