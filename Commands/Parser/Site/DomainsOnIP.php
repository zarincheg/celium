<?php
namespace Commands\Parser\Site;

class DomainsOnIP extends \Command { 
	public function execute() {
		if(preg_match('!<span class="sb_count" id="count">.*?([0-9\.,]+)\s.*?</span>!is', $this->page, $m))
			return(int)str_replace(array(',', '.'), '', $m[1]);

		return null;
	}
}