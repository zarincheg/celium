<?php
namespace Commands\Fetch;
use Http\ExtHttpRequest;

class Web extends \Command {
	public function execute($input = null) {
		if(!is_array($input)) {
			$this->logger->error('Input data must be an array');
			return false;
		}

		$pool = new \HttpRequestPool();

		foreach($input as $item) {
			$http = new ExtHttpRequest($item['key'], $item['url']);
			$http->setOptions(['redirect' => 3]);
			$pool->attach($http);
		}

		try {
			$pool->send();
		} catch(\HttpRequestPoolException $e) {
			$this->logger->error($e->getMessage());
		} catch(\HttpEncodingException $e) {
			$this->logger->error($e->getMessage());
		} catch(\HttpRequestException $e) {
			$this->logger->error($e->getMessage());
		}

		$result = $pool->getFinishedRequests();
		$resultList = [];

		/**
		 * @todo Обработка сетевых ошибок. Если не удалось загрузить страницу, или код ответа не 200
		 * писать об этом в лог и возвращать клиенту с пометкой о неудаче, что типа надо вернуть в очередь задач и попробовать снова
		 * Такие попытки хорошо бы считать и иметь какой-то лимит повторов, после чего считать URL мертвым (возможно на какой-то срок)
		 */

		/** @var $response ExtHttpRequest */
		foreach($result as $response) {
			if(!$response->getResponseData()) {
				$this->logger->warn('Broken URL: '.$response->getUrl().' Code: '.$response->getResponseCode());
			} else {
				$info = $response->getResponseInfo();

				preg_match('!charset=(.*?)!', $info['content_type'], $m);
				preg_match('!^(.*?);!', $info['content_type'], $n);

				$info = ['ip' => $info['primary_ip'],
						 'redirect_url' => $info['redirect_url'],
						 'redirect_count' => $info['redirect_count'],
						 'content_type' => $n[1],
						 'charset' => $m[1],
						 'download_speed' => $info['speed_download'],
						 'loadtime' => round($info['total_time'], 2),
						 'size' => $info['size_download'],
						 'code' => $info['response_code'],
						 'url' => $info['effective_url']];

				$this->logger->info('Success URL: '.$response->getUrl());
				$resultList[] = ['id' => $response->id,
								 'url' => $response->getUrl(),
								 'content' => $response->getResponseBody(),
								 'info' => $info];
			}
		}

		return $resultList;
	}
}