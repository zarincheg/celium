<?php
namespace Commands\Converter;

class PrCy extends \Command {
	protected $data;
	protected $domain;
	private $mongo;

	public function __construct($data, $domain) {
		$this->data = json_decode($data);
		$this->domain = $domain;
		$this->mongo = new \Mongo(\Configure::$get->database->mongodb);
	}

	public function execute() {
		//var_dump($this->data);
		$li = $this->data->liveinternet;
		$whois = [];

		if($li) {
			$visitors = ['hit' => ['month' => $li->month_hit,
						           'week' => $li->week_hit,
						           'day' => $li->day_hit],
						 'vis' => ['month' => $li->month_vis,
						           'week' => $li->week_vis,
						           'day' => $li->day_vis]];
		} else {
			$visitors = null;
		}

		if(isset($this->data->whois)) {
			$currentDate = new \DateTime();
			$creationDate = new \DateTime('@'.strtotime($this->data->creationDate));
			$interval = $currentDate->diff($creationDate);

			$whois['age'] = (int)$interval->format('%y') * 12 + (int)$interval->format('%m');
			$whois['registration'] = $creationDate->format('m.d.y');
			$whois['expiration'] = date('m.d.y', $this->data->expirationDate);

			if(preg_match('!(state|Status):(.*)!', $this->data->whois, $m))
				$whois['verification'] = explode(',', preg_replace('!\s+!', '', $m[2]));
		}

		$doc = ['dmoz' => $this->data->dmoz ? $this->data->dmoz : 0,
				'google' => ['index' => $this->data->googleIndex,
							 'pr' => $this->data->pageRank,
							 'supserp' => 0], // @todo Сделать правду
				'yaca' => ['title' => $this->data->yandexCatalogTitle,
						   'category' => $this->data->yandexCatalogCategory,
						   'description' => $this->data->yandexCatalogDescription],
				'visitors' => $visitors,
				'whois' => $whois,
				'yandex' => [
					'cy' => $this->data->yandexCitation,
					'index' => $this->data->yandexIndex
				]];

		$this->mongo->newaudits->domains->update(['name' => $this->domain],
												 ['$set' => $doc],
												 ['upsert' => true]);
	}
}
