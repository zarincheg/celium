<?php
namespace Commands\Parser\Site;

class Redirect extends \Command {
	protected $domain = null;

	public function __construct($domain) {
		$this->domain = $domain;
	}

	public function execute() {
		$http = new \HttpRequest('http://'.$this->domain);
		$http->send();

		if($http->getResponseInfo('response_code') == 301) {
			return array('hasRedirect' => true,
						 'from' => $http->getResponseInfo('effective_url'),
						 'to' => $http->getResponseInfo('redirect_url'));
		} else {
			return array('hasRedirect' => false);
		}
	}
}