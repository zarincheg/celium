<?php
namespace MongoSchema;
/**
 * Класс описывает поля документа, в котором хранится информация о домене
 */
class DomainDocument extends BaseDocument {
	/**
	 * Доменное имя
	 * @var string
	 */
	protected $name = '';
	protected $key = '';
	/**
	 * Информация о CMS и использующихся на сайте скриптах
	 * @var string
	 */
	protected $appinfo = '';

	/**
	 * Количество страниц
	 * @var int
	 */
	protected $countPages = 0;

	/**
	 * Наличие сайта в каталоге DMoZ
	 * @var bool
	 */
	protected $dmoz = false;

	/**
	 * Кол-во доменов привязанных к IP-адресу сайта
	 * @var int
	 */
	protected $domainsOnIP = 0;

	/**
	 * Список дублирующихся title на страницах
	 * @var array
	 */
	protected $duplicateTitles = false;

	/**
	 * Информация о favicon
	 * @var array
	 */
	protected $favicon =false;

	/**
	 * Данные о сайте из ПС Google: основная выдача, побочная, кол-во в индексе, PR, 
	 * @var type
	 */
	protected $google = array(
			'serp' => 0,
			'supserp' => 0,
			'relatedCount' => 0,
			'relatedTop' => null,
			'index' => 0,
			'pr' => 0
		);

	/**
	 * Беклинки из соломоно
	 * @var array
	 */
	protected $incoming = array(
			'domains' => null,
			'pages' => null
		);

	/**
	 * IP-адрес
	 * @var string
	 */
	protected $ipAddress = '127.0.0.1';

	/**
	 * Зеркала
	 * @var array
	 */
	protected $mirrors = false;

	/**
	 * Информация о несуществуещей странице.
	 * Массив хранит код ответа сервера и содержимое
	 * @var array
	 */
	protected $page404 = array(
			'code' => 404,
			'content' => null
		);

	/**
	 * Информация о robots.txt
	 * @var array
	 */
	protected $robots = array(
			'disallow' => 0,
			'host' => null,
			'sitemap' => null,
			'lines_count' => 0,
			'user_agents' => array()
		);

	/**
	 * Склейка зеркал
	 * @var array
	 */
	protected $wwwRedirect = array('hasRedirect'=>false);

	/**
	 * Название веб-сервера
	 * @var string
	 */
	protected $server = false;

	/**
	 * Информация о карте сайта (sitemap.xml)
	 * @var array
	 */
	protected $sitemap = array(
			'found' => false,
			'modified' => null,
			'valid' => false,
			'url' => '',
			'urls_count' => 0
		);

	/**
	 * Список самых весовых страниц по данным Scales.35cm
	 * @var array
	 */
	protected $top_urls = false;

	/**
	 * Посетители по данным liveinternet, при условии наличия доступа к статистике
	 * @var array
	 */
	protected $visitors = array();

	/**
	 * Если сайт находится в вебархиве, то тут хранится инфа об этом: возраст и ссылка
	 * @var array
	 */
	protected $webarchive = array(
			'age' => false,
			'link' => null
		);

	/**
	 * Данные Whois по домену
	 * @var array
	 */
	protected $whois =  array(
			'registration' => null,
			'expiration' => null,
			'age' => null,
			'verification' => null
		);

	/**
	 * Информация о сайте в каталоге Яндекса, если он там присутствует
	 * @var array
	 */
	protected $yaca = array();

	/**
	 * Информация о сайте в ПС Яндекс
	 * @var array
	 */
	protected $yandex = array(
			'cy'=>0,
			'index'=>0,
			'region'=>'',
			'region_id'=>0,
			'fastlinks'=>array(),
			'chains' => false
	);

	/**
	 * Информация из статистики Яндекс.Вебмастера
	 * @var array
	 */
	protected $yandexWM = array(
			'stats' => null,
			'excluded' => null,
			'indexed' => null,
			'tops' => null,
			'links' => null
		);

}
