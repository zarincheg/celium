<?php
namespace Commands\Analyzers;

class Test extends \AnalyzerCommand {

	protected $name = 'Test';

	public function execute() {
		echo "Test analyzer\n";
		echo "Analytic object: ".$this->object."\n";
	}

	public function depends() {
		return true;
	}

	public function getDepends() {
		return [];
	}
}