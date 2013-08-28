<?php

namespace MongoSchema;
/**
 * Класс описывает поля документа, в котором хранится решение(decision)
 */
class DecisionDocument extends BaseDocument {
	/**
	 * @var Название решения, которые используют анализаторы(analyzers)
	 */
	protected $name;

	/**
	 * @var Список рекомендаций, которые соответствуют этому решению
	 */
	protected $recommendations = [];
}