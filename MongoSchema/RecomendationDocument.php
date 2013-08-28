<?php

namespace MongoSchema;
/**
 * Класс описывает поля документа, в котором хранится информация о домене
 */
class RecomendationDocument extends BaseDocument {
	/**
	 * Название рекомендации. Обычно совпадает с именем аналитического модуля (правила)
	 * @var string
	 */
	protected $name = '';
	/**
	 * Название для отображения в интерфейсе
	 * @var string
	 */
	protected $title = '';
	/**
	 * Позиция правила. Для структуризации в интерфейсе
	 * @var int
	 * @deprecated
	 */
	protected $position = 0;
	/**
	 * Категория. по факту ID родительской рекомендации для структуризации в интерфейсе
	 * @var string
	 * @deprecated
	 */
	protected $category = '';
	/**
	 * ID домена, для которого проведен анализ
	 * @var \MongoId
	 */
	protected $domainID = '';
	/**
	 * Массив текстов рекомендаций, которые были выбраны в результате анализа
	 * @var array
	 */
	protected $texts = array();
	/**
	 * Дополнительные данные результатов анализа. Например для построения графиков или вывода статистики
	 * @var array
	 */
	protected $data = array();
}