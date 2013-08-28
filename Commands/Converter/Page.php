<?php
namespace Commands\Converter;

class Page extends \Command {
	protected $data;
	protected $url;
	protected $keyword;
	protected $domain;
	protected $userID;
	private $mongo;

	public function __construct($data, $url, $keyword, $domain, $userID) {
		$this->data = $data;
		$this->url = $url;
		$this->keyword = $keyword;
		$this->domain = $domain;
		$this->userID = $userID;
		$this->mongo = new \Mongo(\Configure::$get->database->mongodb);
		parent::__construct(null);
	}

	public function execute() {
		$doc = ['advert'         => $this->data['page_advert'],
		        'keywords'       => $this->data['page_keyoccurrence'],
		        'links'          => $this->data['page_links'],
		        'markup'         => $this->data['page_markup'],
		        'meta'           => $this->data['page_meta'],
		        'occurrence'     => $this->data['page_occurrence'],
		        'socialWidgets'  => $this->data['page_social'],
		        'statServices'   => $this->data['page_stat'],
		        'code'           => $this->data['info']['code'],
		        'charset'        => $this->data['info']['charset'],
		        'loadtime'       => $this->data['info']['loadtime'],
		        'content_type'   => $this->data['info']['content_type'],
		        'content_length' => $this->data['info']['size'],
			//'validation' => $this->data['page_valid'],
		        'pr'             => $this->data['google_pr']];

		//В случае, если ключ содержит в себе точки, будет пытаться интерпретировать его
		foreach($doc['meta'] as $k => $v) {
			$newk = str_replace(".", "_", $k);

			if($newk != $k) {
				$doc['meta'][$newk] = $v;
				unset($doc['meta'][$k]);
			}

		}

		$this->mongo->newaudits->pages->update(['url'    => $this->url,
		                                       'keyword' => $this->keyword,
		                                       'domain'  => $this->domain,
		                                       'userID'  => $this->userID], ['$set' => $doc], ['upsert' => true]);
	}
}
