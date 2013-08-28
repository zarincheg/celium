<?php
namespace MongoSchema;
/**
 * Класс описывает поля документа, в котором хранится информация о странице сайта
 */
class PageDocument extends BaseDocument {

	protected $advert = false;
	protected $key = '';

	/**
	 * Кодировка страницы
	 * @var string
	 */
	protected $charset = false; // @todo from new format

	/**
	 * Код ответа веб-сервера при запросе страницы
	 * @var int
	 */
	protected $code = 200;

	/**
	 * Размер страницы из http-заголовка
	 * @var int
	 */
	protected $content_length = 0;

	/**
	 * Тип содержимого, из http-заголовка
	 * @var string
	 */
	protected $content_type = false;

	/**
	 * Показывает закрыта ли страница в robots.txt
	 * @var bool
	 */
	protected $is_robots_banned = false;

	/**
	 * Вхождение поисковых фраз в части страницы
	 * @var array
	 */
	protected $keywords = array(
		'description' => null,
		'h1' => null,
		'main' => null,
		'title' => null
	);

	protected $domain = '';
	protected $domainID = '';
	protected $url = '';
	protected $keyword = '';

	/**
	 * Информация о ссылках на странице
	 * @var array
	 */
	protected $links = array(
		'dynamic' => false,
		'hidden' => false,
		'bad' => false,
		'relative' => null,
		'external' => null,
		'internal' => null,
		'has_session' => null
	);

	/**
	 * Время загрузки страницы в секундах
	 * @var float
	 */
	protected $loadtime = 0; // @todo

	/**
	 * Информация о html-разметке страницы. Наличие/содержимое указанных в массиве тегов
	 * @var array
	 */
	protected $markup = array(
		'displaynone' => false,
		'div' => false,
		'flash' => false,
		'h1' => array(),
		'h2' => array(),
		'hcard' => false,
		'iframe' => false,
		'img' => false,
		'length' => 0,
		'strong' => false,
		'title' => false,
		'noindex' => false,
		'outborder' => false,
		'table' => false);

	/**
	 * Содержимое мета-тегов
	 * @var array
	 */
	protected $meta = array(
		'charset' => null,
		'description' => null,
		'doctype' => null,
		'keywords' => null);

	/**
	 * Частота вхождения слов в текст страницы (тошнота)
	 * @var array
	 */
	protected $occurrence = false;

	/**
	 * Показатель Google PR страницы
	 * @var int
	 */
	protected $pr = false;

	/**
	 * Позиции в поисковых системах
	 * @var array
	 */
	protected $position = array(
		'yandex' => null,
		'google' => null);

	/**
	 * Список страниц с которых стоят ссылки на данной, список родительских страниц, а также внешние ссылки
	 * @var array
	 */
	protected $relations = array(
		'childs' => null,
		'external' => null,
		'parents' => null);

	/**
	 * Вес страницы по Scales.35cm
	 * @var int
	 */
	protected $scales_weight = 0;

	/**
	 * Данные о сниппите в выдаче ПС Яндекс
	 * @var array
	 */
	protected $snippet = array(
		'break' => false,
		'length' => 0,
		'uppercase' => false);

	/**
	 * Информация о наличии виджетов социальных сетей
	 * @var array
	 */
	protected $socialWidgets = array(
		'fb' => false,
		'gplus' => false,
		'odnoklassniki' => false,
		'twitter' => false,
		'vk' => false);

	/**
	 * Проверка орфографии текста через Яндекс.Spellchecker.
	 * В массиве хранятся слова с ошибками и общее кол-во ошибок
	 * @var array
	 */
	protected $spellcheck = array(
		'count' => 0,
		'words' => false);

	/**
	 * Список сервисов сбора статистики установленных на странице
	 * @var array
	 */
	protected $statServices = array();

	/**
	 * Общий вес страницы. Включая весю медиа и графический контент
	 * @var float
	 */
	protected $total_weight = false;

	/**
	 * Уникальность текста на странице.
	 * Массив содержит список уникальных кусков и все элементы, по которым проверялась страница
	 * @var type
	 */
	protected $uniqueText = array(
		'unique' => false,
		'all' => false);

	/**
	 * Содержит информацию о валидности html и css кода согласно стандартам w3c.
	 * Хранит кол-во ошибок в css и html
	 * @var type
	 */
	protected $validation = array(
		'css' => 0,
		'html' => 0
	);

}
