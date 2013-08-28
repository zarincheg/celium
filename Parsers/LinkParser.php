<?php

namespace Parsers;

require_once(dirname(dirname(__DIR__)) . '/etc/autoload.sys.php');

/**
 * @package 35cm
 *
 * @todo тесты
 * отвязать от определения только href=""|href=''|href=(.* |) - вот и получится простой дом парсер
 * измерить скорость потом и делать
 * Dom::create('<a href="/"></a>')->a->href
 * img->src
 *
 * либо использовать какой-нибудь стандартный парсер, посмотреть sexp`s
 * общий вид http://cs.nyu.edu/rgrimm/teaching/fa03-web/a3.html
 * даже готовый парсер http://common-lisp.net/project/xmls/
 */
class LinkParser {
	// (text near left border)(A)(text near right border)
	private $regexp = '!([^>^\.^\!^\?]*)<a(.*?href.*?)>(.*?)</a>([^<^\.^\!^\?]*)!is';
	// (href="abc")|(href='abc')|(href=abc)
	private $link = '!(href\s*=\s*"(.*?)"|href\s*=\s*\'(.*?)\'|href\s*=\s*([^\s]+)[\s]*)!is';
	private $nofollow = '/rel.*?=.*?nofollow/is';
	private $output = array();
	protected $contents = '';

	public function parse($contents) {
		$this->contents = $contents;
		// init
		$this->output = array('title' => '', 'urls'=>array());

		$this->getTitle();
		$this->getBaseTag();
		$this->prepareContents();
		$this->noindex();
		$this->findLinks($this->contents);
		return $this->output;
	}

	protected function getTitle() {
		if(preg_match('!<title.*?>(.*?)</title>!is', $this->contents, $m))
			$this->output['title'] = $m[1];
	}

	protected function getBaseTag() {
		if(preg_match('/<base.*?href=([\']{1}(.*?)[\']{1}|["]{1}(.*?)["]{1}).*?>/is', $this->contents, $m))
			$this->output['base'] = trim($m[3]);
	}

	/**
	 * remove head, scripts, css, textarea, comments, & mb we need remove &lt;'s
	 */
	protected function prepareContents() {
		$this->contents = preg_replace(array('/.*<body(\s.*?|)>/is',
											 '!<style.*?</style>!is',
											 '!<script.*?</script>!is',
											 '!<textarea.*?</textarea>!is',
											 '/<!--.*?-->/is'),
									   array('[HEAD]<body>', '[CSS]', '[SCRIPT]', '[TEXTAREA]', '[COMMENT]'),
									   $this->contents);
	}

	// match, replace -> replace
	private function noindex() {
		$t = $this;
		$c = function($m) use($t) { if(count($m)>0){$t->findLinks($m[0], array('noindex' => 1));} return ''; };
		$this->contents = preg_replace_callback('!<noindex.*?</noindex>!is', $c, $this->contents);
	}

	// public cause noindex.Closure
	public function findLinks($contents, $custom_fields=array()) {
		preg_match_all($this->regexp, $contents, $links);
		for($i = 0; $i < count($links[0]); ++$i) {
			$value = array('url' => '', 'text' => '', 'lt' => '', 'rt' => '');
			if($custom_fields) $value = array_merge($value, $custom_fields);
			preg_match($this->link, $links[2][$i], $link);
			foreach(array(2, 3, 4) as $index) {
				if(isset($link[$index]) && $link[$index]) {
					$value['url'] = $link[$index];
					break;
				}
			}

			$value['text'] = $this->cleanText($links[3][$i]);
			$value['lt'] = $this->cleanText($links[1][$i]);
			$value['rt'] = $this->cleanText($links[4][$i]);

			// if link has rel=nofollow
			if(preg_match($this->nofollow, $links[2][$i]))
				$value['nofollow'] = 1;

			$this->output['urls'][] = $value;
		}
	}

	private function cleanText($anchor) {
		return trim(preg_replace(array('/<img .*?>/is', '/<.*?>/is', '/[\s\n\r\t]+/is'), array('[IMG]', '', ' '), $anchor));
	}
}
