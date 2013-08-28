<?php
namespace MongoSchema;

class BaseDocument {

	public function __construct($data = array()) {
		if($data) {
			foreach($data as $key => $value) {
				$this->$key($value);
			}
		}
	}


	public function __call($name, $arguments) {
		$reflection = new \ReflectionObject($this);
		$properties = $reflection->getDefaultProperties();

		if(!array_key_exists($name, $properties)) {
			throw new \Exception('Bad field ' . $name);
		}
		if(!$arguments) {
			return $this->$name;
		}

		if(is_array($this->$name) && count($this->$name) > 0) {
			$this->$name = array_merge($this->$name, $arguments[0]);
		} else {
			$this->$name = $arguments[0];
		}
	}

	/**
	 * << $document->yaca['huyaka'];
	 */
	public function __get($name) {
		if(!isset($this->$name)) {
			throw new \Exception('Bad field ' . $name);
		}

		return $this->$name;
	}

	public function toArray() {
		$result = array();
		$r = new \ReflectionClass($this);
		$properties = $r->getProperties(\ReflectionProperty::IS_PROTECTED);

		foreach($properties as $property) {
			$result[$property->name] = $this->{$property->name};
		}

		return $result;
	}

}
