<?php
/**
 * Производит канонизацию текста, удаляя мусор, спецсимволы, html и прочее
 *
 * @author Kirill Zorin aka Zarin <zarincheg@gmail.com>
 * @copyright Copyright (c) 2011, Kirill Zorin, SeoStopol LLC
 * 
 */
class Canonization {
	public function clearHTML($text) {
		return strip_tags($text);
	}
	
	public function clearPunct($text) {
		$text = preg_replace('![\?\!\,\.\'\"\:;\_\%\#\@\*\(\)\+\[\]\{\}/&\|«»©<>\-–—“]!ui', '', $text);
		return preg_replace('!\s{2,}!ui', ' ', $text);
	}
	
	// @todo Исправить, заменяет в том числе и цифры
	public function clearShorts($text) {
		return preg_replace(array('!\b\w{1,3}\b!iu', '![\n\t\s\-]{1,}!'), array('', ' '), $text);
	}
	
	public function clearTrash($html) {
		return preg_replace(array("!<script.*?</script>!is", '!<style.*?</style>!is', '!<meta.*?>!is', '/<!--.*?-->/is'), '', $html);
	}
	
	public function text($html) {
		$html = $this->clearTrash($html);
		$text = $this->clearHTML($html);
		$text = html_entity_decode($text, ENT_NOQUOTES, 'UTF-8');
		$text = $this->clearPunct($text);
		$text = $this->clearShorts($text);
		return $text;
	}

	public function allText($html) {
		$html = $this->clearTrash($html);
		$text = preg_replace(array('!<textarea.*?</textarea>!is', '!<.*?>!is', '!&.*?;!is', '![0-9\n \s\r\t]+!is'), ' ', $html);
		return trim($this->clearPunct($text));
	}

	/**
	 * Оставляем пунктуацию
	 * @param string $html Текст
	 * @return string
	 */
	public function ptext($html) {
		$html = $this->clearTrash($html);
		$text = $this->clearHTML($html);
		$text = html_entity_decode($text, ENT_NOQUOTES, 'UTF-8');
		$text = $this->clearShorts($text);
		return $text;
	}

	public function sentences($html) {
		$html = $this->clearTrash($html);
		$text = $this->clearHTML($html);
		$text = preg_replace('![\n\t\s\-]{1,}!', ' ', $text);
		return preg_split('~(?<=[?!.])\s+~uis', $text);
	}
}

?>
