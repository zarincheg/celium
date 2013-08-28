<?php
namespace Parsers;
/**
* Wappalyzer php version
*/
class Wappalyzer extends WappalyzerApps {
	
	public static function analyze($url, $html, $headers) {
		if(!is_array($headers))
			$headers = self::parseHeaders($headers);
		
		$meta = self::parseMeta($html);
		$scripts = self::parseScripts($html);
		$data = array(
			'url' => $url,
			'html' => $html,
			'meta' => $meta,
			'headers' => $headers,
			'scripts' => $scripts
		);
		return self::detect($data);
	}
	
	private static function parseScripts($html) {
		$scripts = array();
		preg_match_all('/<script[^>]+src\s*=\s*([\'"])([^<>"\']+)\\1[^>]*>/i', $html, $scripts);
		$scripts = $scripts[2];
		return $scripts;
	}
	
	private static function parseMeta($html) {
		$meta = array();
		preg_match_all('/<meta([^>]+)>/i', $html, $metaContents);
		$metaContents = $metaContents[1];
		foreach ($metaContents as $metaContent) {
			preg_match('/name\s*=\s*([\'"])([^<>"\']+)\\1/i', $metaContent, $name);
			preg_match('/content\s*=\s*([\'"])([^<>"\']+)\\1/i', $metaContent, $content);
			if ($name && $content) {
				$meta[trim($name[2])] = trim($content[2]);
			}
		}
		return $meta;
	}
	
	private static function parseHeaders($text) {
		$headers = array();
		$lines = preg_split('/[\r\n]+/', $text);
		foreach ($lines as $header) {
			if (strpos($header, ":") !== false) {
				list($key, $value) = explode(":", $header, 2);
				$headers[trim($key)] = trim($value);
			}
		}

		return $headers;
	}
	
	private static function detect($data) {
		$detected = array();
		foreach (self::$apps as $app => $conditions) {
			foreach ($conditions as $type => $condition) {
				if (isset($detected[$app])) {
					break;
				}
				switch ($type) {
					case 'url':
						if (self::testUrl($data['url'], $condition)) {
							$detected[$app] = $conditions;
						}
					break;
					case 'html':
						if (self::testHtml($data['html'], $condition)) {
							$detected[$app] = $conditions;
						}
					break;
					case 'script':
						if (self::testScript($data['scripts'], $condition)) {
							$detected[$app] = $conditions;
						}
					break;
					case 'meta':
						if (self::testMeta($data['meta'], $condition)) {
							$detected[$app] = $conditions;
						}
					break;
					case 'headers':
						if (self::testHeaders($data['headers'], $condition)) {
							$detected[$app] = $conditions;
						}
					break;
				}	
			}
		}
		$result = array();
		foreach ($detected as $appName => $appData) {
			$category = self::$categories[$appData['category']];
			$result[$category][$appName] = array(
				'name' => $appName,
				'icon' => str_replace(' ', '_', $appName).'.png',
				'category' => $category
			);
		}
		return $result;
	}
	
	private static function testUrl($url, $condition) {
		return (boolean) preg_match($condition, $url);
	}	
	
	private static function testHtml($html, $condition) {
		return (boolean) preg_match($condition, $html);
	}
	
	private static function testScript($scripts, $condition) {
		foreach ($scripts as $script) {
			if (preg_match($condition, $script)) {
				return true;
			}
		}
		return false;
	}
	
	private static function testHeaders($headers, $condition) {
		foreach ($headers as $name => $value) {
			foreach ($condition as $conditionName => $conditionRegexp) {
				if (strtolower($name) != strtolower($conditionName)) {
					continue;
				}
				
				if(is_array($value)) {
					foreach($value as $val)
						if (preg_match($conditionRegexp, $val))
							return true;
				}
				else {
					if (preg_match($conditionRegexp, $value)) {
						return true;
					}	
				}
				
			}
		}
		return false;
	}
	
	
	private static function testMeta($meta, $condition) {
		foreach ($meta as $name => $value) {
			foreach ($condition as $conditionName => $conditionRegexp) {
				if (strtolower($name) != strtolower($conditionName)) {
					continue;
				}
				if (preg_match($conditionRegexp, $value)) {
					return true;
				}	
			}
		}
		return false;
	}
}