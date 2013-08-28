<?php

namespace MongoSchema;
/**
 * Класс описывает поля документа, в котором хранится решение(decision)
 */
class AnalyzerDocument extends BaseDocument {
	/**
	 * @var Название анализатора, которое соотв. полю name в классе.
	 */
	protected $name = null;

	/**
	 * @var Описание анализатора. Что и как он делает, какие данные использует, какие решения может принимать
	 */
	protected $description = null;
}