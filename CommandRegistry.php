<?php
namespace Celium;
/**
 * Реестр комманд и алиасов к ним
 * @todo Перенести описание в конфиг-файл
 */
class CommandRegistry {
	static private $root = '\Celium\Commands';
	static private $list = [
		'google' => ['ns' => '\Parser\Google',
					 'commands' => ['getrelated' => 'GetRelated',
					 				'indexcount' => 'IndexCount',
					 				'pr' => 'Pr']],
		'page' => ['ns' => '\Parser\Page',
				   'commands' => ['advert' => 'Advert',
				   				  'favicon' => 'Favicon',
				   				  'images' => 'Images',
				   				  'keyoccurrence' => 'KeywordsOccurrence',
				   				  'links' => 'Links',
				   				  'markup' => 'Markup',
				   				  'meta' => 'Meta',
				   				  'occurrence' => 'Occurrence',
				   				  'social' => 'Social',
				   				  'stat' => 'StatServices',
				   				  'valid' => 'Validation']],
		'site' => ['ns' => '\Parser\Site',
				   'commands' => ['dmoz' => 'Dmoz',
				   				  'onip' => 'DomainsOnIP',
				   				  'incoming' => 'Incoming',
				   				  'redirect' => 'Redirect',
				   				  'robots' => 'Robots',
				   				  'sitemap' => 'Sitemap',
				   				  'visit' => 'Visitors',
				   				  'archive' => 'Webarchive',
				   				  'whois' => 'Whois']],
		'yandex' => ['ns' => '\Parser\Yandex',
					 'commands' => ['chains' => 'Chains',
					 				'fastlinks' => 'FastLinks',
					 				'snippet' => 'Snippet',
					 				'spell' => 'Spellcheck']],
		'app' => ['path' => '\Parser\App'],
		'page_converter' => ['path' => '\Converter\Page'],
		'domain_converter' => ['path' => '\Converter\Domain'],
		'prcy_converter' => ['path' => '\Converter\PrCy'],
		'onip_converter' => ['path' => '\Converter\OnIp'],
		'miralab_converter' => ['path' => '\Converter\Miralab'],
		'sitemap_converter' => ['path' => '\Converter\Sitemap'],
		'webarchive_converter' => ['path' => '\Converter\Webarchive'],
		'robots_converter' => ['path' => '\Converter\Robots'],
		
		'analyzer' => ['ns' => '\Analyzers',
					   'commands' => ['test' => 'Test']],
		'fetch' => ['ns' => '\Fetch',
					'commands' => ['web' => 'Web',
					 			   'miralab' => 'Miralab',
					   			   'prcy' => 'Prcy']],
		'test' => ['path' => '\Test'],
		'pipe_test' => ['path' => '\TestPipe']
	];

	static public function get($command, $params = []) {
		$name = explode('.', $command);
		$group = self::$list[$name[0]];
		
		if(isset($group['path'])) {
			$commandClass = self::$root.$group['path'];
		} else {
			$path = $group['ns'].'\\'.$group['commands'][$name[1]];
			$commandClass = self::$root.$path;
		}
		// try catch
		$class = new \ReflectionClass($commandClass);

		if($params)
			return $class->newInstanceArgs($params);
		else
			return $class->newInstance();
	}
}