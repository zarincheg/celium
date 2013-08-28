<?php
namespace Parsers;
require_once(dirname(dirname(__DIR__)) . '/lib/phpmorphy/src/common.php');

/**
 * @todo Добавить парсер полезного текста и подсчет кол-ва символов
 */
class Page {
	private $body;
	static private $parser = null;

	/**
	 * @var \DOMDocument
	 */
	private $document;

	/**
	 * @var string корректное отображение кодировки при парсинге DOMDocument. Страницы с различными <!DOCTYPE html>
	 * будут отображаться одинаково.
	 */
	private $utf8Meta = '<meta http-equiv="content-type" content="text/html; charset=utf-8">';

	public function __construct($body) {
		$this->body = $body;
		$this->document = new \DOMDocument();
		@$this->document->loadHTML($this->utf8Meta . $body);
	}

	public function setBody($page) {
		$this->body = $page;
	}

	public function setDocument(\DOMDocument $document) {
		$this->document = $document;
	}

	static public function instance($page) {
		if(self::$parser === null) {
			self::$parser = new Page($page);
		}

		if(self::$parser instanceof Page) {
			self::$parser->setBody($page);
			self::$parser->setDocument(new \DOMDocument());
			@self::$parser->document->loadHTML(self::$parser->utf8Meta . $page);
			return self::$parser;
		} else {
			return false;
		}
	}

	/**
	 * Проверяет наличие скриптов по сбору статистики от различных сервисов и возвращает информацию в массиве:
	 * statServices: [liveinternet, google, yandex]
	 */
	public function statServices() {
		$result = array();

		if(preg_match('!href=(\'|\")http://www\.liveinternet\.ru/click(\'|\")!is', $this->body, $m))
			$result[] = 'liveinternet';

		if(preg_match('!Ya\.Metrika!is', $this->body))
			$result[] = 'yandex';

		if(preg_match('!google\-analytics\.com/ga\.js!is', $this->body))
			$result[] = 'google';

		return $result;
	}

	/**
	 * @todo Определение несанкционированных ссылок (bad)
	 * @todo Динамические ссылки
	 * Возвращает статистику о ссылках на странице в виде массива:
	 * links => { dynamic, hidden, external, internal, relative, bad, has_session}
	 *
	 * @param $url
	 *
	 * @internal param $body
	 * @return array
	 */
	public function links($url) {
		$groupedLinks = ['hidden' => false,
		                 'relative' => false,
		                 'external' => false,
		                 'internal' => false,
		                 'has_session' => false];

		//hiddenLinks
		preg_match('!display\s?:\s?none.*>.*<a.*>.*</a>!i', $this->body, $result);
		preg_match('!<a.*display\s?:\s?none.*>!i', $this->body, $result1);
		$groupedLinks['hidden'] = ($result || $result1) ? true : false;

		//linksCount()
		$l = new \Parsers\Links();
		$links = $l->get($this->body, 'http://'.parse_url($url, PHP_URL_HOST));

		foreach($links as $link) {
			if(isset($link['relative']))
				$groupedLinks['relative'][] = $link['absolute'];

			if($link['external'])
				$groupedLinks['external'][] = $link['absolute'];
			else
				$groupedLinks['internal'][] = $link['absolute'];
		}

		preg_match_all('!<a.*?href=[\'\"](.*?SESSID|SessionID.*?)[\'\"].*?>!i', $this->body, $m);
		$groupedLinks['has_session'] = $m[1];

		return $groupedLinks;
	}

	/**
	 * Возвращает список рекламных площадок, которые присутствуют на странице
	 *
	 * @return array Список рекламных площадок
	 */
	public function advert() {
		$advert = array();
		$images = $this->images($this->body);
		$advertURL = explode(PHP_EOL, file_get_contents(__DIR__ . '/../../data/adv'));

		foreach($images as $image) {
			$host = parse_url($image['src'], PHP_URL_HOST);

			if(in_array($host, $advertURL))
				$advert[] = $host;
		}

		return $advert;
	}

	/**
	 * @todo Протестировать, кажется сломалось частично
	 * Проверяет наличие виджетов соц. сетей на странце. Возвращает массив следующей структуры:
	 * widgets => { fb => [like, likebox, comments],
	 *			  vk => [like, comments, group],
	 *			  twitter => bool
	 *			  odnoklassniki => bool
	 *			}
	 */
	public function social() {
		$body = preg_replace(array('/<!--.*?-->/is'), '',  $this->body);

		$widgets = array('fb' => (bool)preg_match('!src=[\'\"].*?facebook\.com.*?!is', $body, $m),
		                 'vk' => (bool)preg_match('!<script.*?>.*?VK\.Widgets\.([a-z]+).*?</script>!is', $body),
		                 'twitter' => (bool)preg_match('!src=[\'\"].*?twitter\.com.*?[\'\"]!is', $body),
		                 'odnoklassniki' => (bool)preg_match('!src=[\'\"].*?odnoklassniki\.ru.*?[\'\"]!is', $body),
		                 'gplus' => (bool)preg_match('!<g:plusone.*?>.*?</g:plusone>!is', $body));
		return $widgets;
	}

	// @todo Обратить внимание на этот метод. Решить как получать эту инфу при новой схеме работы
	/**
	 * Возвращает техническую информацию о странице
	 * - код ответа
	 * - размер содержимого
	 * - время загрузки
	 * - общий вес старницы, со всеми картинками, скриптами и прочим
	 * - тип контента
	 * - кодировка
	 */
	public function info() { }

	public function occurrence() {
		$occurrence = new \WordsOccurrence();

		$markup = $this->markup();
		$result  = array('main' => $occurrence->frequency($this->body),
		                 'body' => $occurrence->frequency($markup['body']),
						 'title' => $occurrence->frequency($markup['title']),
						 'strong' => $occurrence->frequency(implode(' ', $markup['strong'])));
		$h = array_merge($markup['h1'], $markup['h2'], $markup['h3'], $markup['h4'], $markup['h5'], $markup['h6']);
		$result['h'] = $occurrence->frequency(implode(' ', $h));

		return $result;
	}

	/**
	 * @todo Сейчас нет поддержки многословников. Используется только первое слово.
	 * Возвращает информацию о кол-ве вхождений ключевых слов в различные части страницы
	 * keywords: { title, description, h1, main }
	 */
	public function keywordsOccurrence($word) {
		$analyzer = new \WordsOccurrence();
		$canonization = new \Canonization();

		$meta = $this->meta();
		$markup = $this->markup();

		$words = explode(' ', $word);
		$word = $words[0];

		$descriptionOccure = isset($meta['description']) ? $analyzer->occurreWord($word, $meta['description']) : 0;

		return array('description' => $descriptionOccure,
		             'h1' => $analyzer->occurreWord($word, implode(' ', $markup['h1'])),
		             'main' => $analyzer->occurreWord($word, $canonization->allText($this->body)),
		             'title' => $analyzer->occurreWord($word, $markup['title']));
	}

	/**
	 * @param $url string URL страницы
	 *
	 * @return array Массив из двух элементов. Ошибки в HTML разметке и ошибки в css
	 */
	public function validation($url) {
		$css = file_get_contents('http://jigsaw.w3.org/css-validator/validator?uri=' . $url);
		$html = file_get_contents('http://validator.w3.org/check?uri=' . urlencode($url));
		$result = array('html' => 0, 'css' => 0);

		if(preg_match('!(.*) Errors!', $html, $m))
			$result['html'] = (int)$m[1];

		if(preg_match('!<a href="#errors">Errors \((.*?)\)</a>!', $css, $m))
			$result['css'] = (int)$m[1];

		return $result;
	}

	/**
	 * @return array Массив с информацией об изображениях на странице
	 */
	public function images() {
		$list = array();
		/**
		 * @var $node \DOMElement
		 */
		foreach($this->document->getElementsByTagName('img') as $node) {
			$list[] = array('src' => $node->getAttribute('src'),
			                'title' => $node->getAttribute('title'),
			                'alt' => $node->getAttribute('alt'));
		}

		return $list;
	}

	/**
	 * @return array Массив информации из мета-тегов страницы
	 */
	public function meta() {
		$meta = array();
		$meta['charset'] = $this->document->encoding;
		$meta['doctype'] = $this->document->doctype !== null ? $this->document->doctype->internalSubset : null;
		/**
		 * @var $node \DOMElement
		 */
		foreach($this->document->getElementsByTagName('meta') as $node) {
			$name = $node->getAttribute('name');

			if(!empty($name))
				$meta[mb_strtolower($name, 'UTF-8')] = $node->getAttribute('content');
		}

		$canon = new \Canonization();
		$flatText = $canon->text($this->body);
		$meta['textsize'] = strlen($flatText);

		return $meta;
	}

	/**
	 * @param $tag Название тега, тексты из которых нам нужны
	 *
	 * @return array Массив с текстами из тегов
	 */
	public function getTagText($tag) {
		$texts = array();

		/**
		 * @var $node \DOMElement
		 */
		foreach($this->document->getElementsByTagName($tag) as $node) {
			$texts[] = $node->textContent;
		}

		return $texts;
	}

	/**
	 * Собирает инфу по тегам
	 */
	public function markup() {
		$canonization = new \Canonization();
		$body = $canonization->clearTrash($this->body);

		$markup = array('h1' => $this->getTagText('h1'),
		                'h2' => $this->getTagText('h2'),
		                'h3' => $this->getTagText('h3'),
		                'h4' => $this->getTagText('h4'),
		                'h5' => $this->getTagText('h5'),
		                'h6' => $this->getTagText('h6'));

		$title = preg_match('!<title.*?>(.*?)</title>!is', $body, $title) ? $title[1] : '';
		$title = $canonization->allText($title);

		$noindex = array();
		preg_match_all('!<noindex.*?>(.*?)</noindex>!is', $body, $m);
		preg_match('!<body.*?>(.*?)</body>!is', $body, $bodyContent);

		foreach($m[1] as $text) {
			$noindex[] = $canonization->allText($text);
		}

		$markup = array_merge($markup, array(
			'title' => $title,
			'length' => floor(mb_strlen($canonization->allText($body), 'UTF-8') % 100),
			'img' => self::images($body),
			'noindex' => mb_strlen(implode('', $noindex), 'UTF-8'),
			'strong' => array_merge($this->getTagText('b'), $this->getTagText('strong')),
			'displaynone' => (bool)preg_match('!display\s?:\s?none!is', $body),
			'div' => (bool)preg_match('!<div([ ]+.*?|)>.*?</div>!is', $body),
			'table' => (bool)preg_match('!<table([ ]+.*?|)>.*?</table>!is', $body),
			'outborder' => (bool)preg_match('!</body>.*?</html>[^\s]+!is', $body),
			'hcard' => (bool)preg_match('!class=.?vcard!is', $body),
			'iframe' => (bool)preg_match('!<iframe([ ]+.*?|)>.*?</iframe>!is', $body),
			'flash' => (bool)preg_match('!type=.*?application/x\-shockwave\-flash!is', $body),
			'body' => isset($bodyContent[1]) ? $bodyContent[1] : null
		));

		return $markup;
	}

	/**
	 * Указание favicon в верстке страницы
	 *
	 * @param $domain
	 *
	 * @return bool
	 */
	public function favicon($domain) {
		http_get('http://'.$domain, [], $info);
		$ico['exists'] = $info['response_code'] == 200 ? true : false;
		$ico['declare'] = (bool)preg_match('/<link .*?(rel.*?=.*?href.*?=.*?favicon|href.*?=.*?favicon.*?rel.*?=).*?>/is', $this->body);

		return $ico;
	}
}