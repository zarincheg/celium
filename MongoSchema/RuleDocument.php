<?php

namespace MongoSchema;
/**
 * Класс описывает поля документа, в котором хранится информация о домене
 */
class RuleDocument extends BaseDocument {
	/**
	 * Название/ID правила
	 * @var string
	 */
	protected $_id = '';
	/**
	 * Название для отображения в интерфейсе
	 * @var string
	 */
	protected $title = '';
	/**
	 * ID родительского правила. Для структуризации в интерфейсе
	 * @var string
	 * @deprecated
	 */
	protected $parent = '';
	/**
	 * Позиция правила. Для структуризации в интерфейсе
	 * @var int
	 * @deprecated
	 */
	protected $position = 0;
	/**
	 * Активность правила. Если установлено в true, то будет использовано при генерации аудита
	 * @var bool
	 */
	protected $enabled = false;
	/**
	 * JS-код правила(аналитика)
	 * @var string
	 */
	protected $code = '';
	/**
	 * Массив текстов рекомендация, которые могут быть использованы в качестве результата анализа
	 * @var array
	 */
	protected $recomendations = array();
	/**
	 * Флаг отображения заголовка в интерфейсе
	 * @var bool
	 * @deprecated
	 */
	protected $titleView = true;
}