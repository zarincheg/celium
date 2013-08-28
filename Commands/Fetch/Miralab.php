<?php
namespace Commands\Fetch;

class Miralab extends \Command {
	public function execute($input = null) {
		if(!is_array($input)) {
			$this->logger->error('Input data must be an array');
			return false;
		}

		$content = [];
		$miralab = new \API\Miralab();
		$miralab->setKey(\Configure::$get->api->miralab);

		$result = $miralab->yandexSearch($input['keyword'] . ' ' . $input['domain']);

		if(count($result->CommonSearchResultsItemExt[0]->QuickLinks->LinkResultItem)) {
			$fastLinks = (array)$result->CommonSearchResultsItemExt[0]->QuickLinks;
			$content['fastlinks'] = $fastLinks['LinkResultItem'];
		}

		$content['snippet'] = (string)$result->CommonSearchResultsItemExt[0]->Snippet;
		$content['chains'] = (bool)count($result->CommonSearchResultsItemExt[0]->NavigationChains);
		$content['key'] = $input['key'];

		return $content;
	}
}