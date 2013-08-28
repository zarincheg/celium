<?php
/**
 * Реализует инициализацию анализаторов. И содержит базовые функции.
 */
abstract class AnalyzerCommand /* implements Command */ {
	/**
	 * @var Объект анализа. Может быть что угодно: домен, страница, массив данных. Анализатор сам решает что ему делать.
	 */
	protected $object = null;

	public function init() {
		echo "Init analyzer: ".$this->name."\n";

		$mongo = new \MongoClient(\Configure::$get->database->mongodb);
		$db = $mongo->selectDB(\Configure::$get->database->analytics);
		$doc = $db->analyzers->findOne(['name' => $this->name]);
		
		if($doc) {
			echo "Analyzer ".$this->name." already initialized\n";
			return true;
		}

		$analyzerDoc = new \MongoSchema\AnalyzerDocument(['name' => $this->name]);
		$result = $db->analyzers->insert($analyzerDoc->toArray());

		if(!$result)
			throw new Exception("Analyzer can't initialized. Database insertion fails.");

		echo "Success initialization!\n";
	}

	/**
	 * Устанавливает субъект анализа
	 * @param mixed
	 */
	public function object($object) {
		$this->object = $object;
	}

	public function name() {
		if(!$this->name)
			return null;

		return $this->name;
	}

	/**
	 * Проверяет зависимости и/или наличие необходимых для анализа данных
	 * @return bool
	 */
	abstract public function depends();

	/**
	 * Возвращает информацию о зависимостях и необходимых для анализа данных.
	 * @return array
	 */
	abstract public function getDepends();
}