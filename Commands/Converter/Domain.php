<?php
namespace Commands\Converter;

class Domain extends \Command {
	protected $data;
	protected $url;
	protected $keyword;
	protected $domain;
	private $mongo;

	public function __construct($data, $url, $keyword, $domain) {
		$this->data = $data;
		$this->domain = $domain;
		$this->mongo = new \Mongo(\Configure::$get->database->mongodb);
		parent::__construct(null);
	}

	public function execute() {
		$doc = [//'domainsOnIP' => $this->data['site_onip'],
		        //'incoming' => $this->data['site_incoming'],
		        'wwwRedirect' => $this->data['site_redirect'],
		        //'robots' => $this->data['site_robots'],
		        //'sitemap' => $this->data['site_sitemap'],
		        //'webarchive' => $this->data['site_archive'],
		        //'whois' => $this->data['site_whois'],
				'ipAddress' => $this->data['info']['ip'],
				'favicon' => $this->data['page_favicon']];
		$this->mongo->newaudits->domains->update(['name' => $this->domain],
												 ['$set' => $doc],
												 ['upsert' => true]);
	}
}
