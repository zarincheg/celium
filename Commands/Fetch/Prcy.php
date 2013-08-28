<?php
namespace Commands\Fetch;

class Prcy extends \Command {
	public function execute($input = null) {
		if(!is_array($input)) {
			$this->logger->error('Input data must be an array');
			return false;
		}

		$prcy = new \API\PrCy();
		$result = $prcy->getDomainStats($input['domain']);
		$result->key = $input['key'];

		return $result;
	}
}