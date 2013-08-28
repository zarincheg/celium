<?php
namespace Http;
/**
 * Description of ExtHttpRequest
 * 
 * @author Kirill Zorin aka Zarin <zarincheg@gmail.com>
 * 
 */
class ExtHttpRequest extends \HttpRequest {
	public $id = null;
	private $log;
	
	public function __construct($id, $url = null, $request_method = HTTP_METH_GET, $options = array()) {
		$this->id = $id;
		//$this->log = new \Log\Message('/tmp/35cm/fetch/');
		parent::__construct($url, $request_method, $options);
	}
	
	public function getResponseData() {
		//@ Вот сюда проверку на коды и запись в лог
		/*
		if($this->getResponseCode() != 200) {
			$this->log->write('URL:'.$this->getUrl().' '.$this->getResponseCode());
			return false;
		}
		 * @todo Логи наверное все таки не на прямую писать, а возвращать или хз, короче сделать возможность разделения, а то каша будет
		 */
		
		$data = parent::getResponseData();
		$data['body'] = $this->toUtf($this->getResponseHeader('Content-Type'), $data['body']);
		return $data;
	}

	public function getResponseBody() {
		//@ Вот сюда проверку на коды и запись в лог
		/*
		if($this->getResponseCode() != 200) {
			$this->log->write('URL:'.$this->getUrl().' '.$this->getResponseCode());
			return false;
		}
		 * @todo Логи наверное все таки не на прямую писать, а возвращать или хз, короче сделать возможность разделения, а то каша будет
		 */
		
		$data = parent::getResponseBody();
		return $this->toUtf($this->getResponseHeader('Content-Type'), $data);
	}

	/**
	 * http://habrahabr.ru/post/147843/, http://gamejam.ru/files/codepages.png
	 *
	 * Если на самом деле кодировка текста не utf8, быть фейлу при сохранении даты в mongodb, при json_encode
	 *
	 * Пример страницы в вин кодировке без возможности определить ее http://honda-fit.ru/alltopics/alltopics1.html
	 *
	 * We can try remove all non-utf chars, see http://sosnovskij.ru/
	 * iconv('UTF-8', 'UTF-8//IGNORE', $text);
	 *
	 * iconv UTF-8//IGNORE will fail at:
	 * page: http://moscow.olx.ru/event-iid-436923825
	 * text: &ETH;�&ETH;&frac12;&Ntilde;�&Ntilde;�&Ntilde;�&Ntilde;�&ETH;&frac14;&ETH;&micro;&ETH;&frac12;&Ntilde;�&Ntilde;� &ETH;&cedil; &ETH;&sup2;&ETH;&cedil;&ETH;&acute;&ETH;&para;&ETH;&micro;&Ntilde;�&Ntilde;�
	 *
	 */
	public function toUtf($header, $body) {
		// if image
		if(preg_match('!.*image.*!is', $header)) return $body;

		// if page has charset in header
		$charset = preg_match('! [^=]+=(.*?)$!i', $header, $m) ? strtolower(trim($m[1])) : '';
		// if in body
		if(!$charset)
			$charset = preg_match('!<meta.*?charset=([a-z0-9\-]{3,16}).*?>!is', $body, $m) ? strtolower(trim($m[1])) : '';

		// if has no charset in body && header
		if(!$charset)
			$charset = preg_match('/[åêòðèô]+/s', $body) ? 'cp1251' : '';

		try {
			if(!in_array($charset, array('', 'utf8', 'utf-8')))
				$body = iconv($charset, 'utf-8//IGNORE', $body);
		} catch(\Exception $e) {};

		try {
			$body = iconv('UTF-8', 'UTF-8//IGNORE', $body);
		} catch(\Exception $e) {};

		return $body;
	}
}
