<?php
/**
 * Description of class
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

namespace Services\PipelineExampleNode;


class Manager extends \Services\PipelineNode\Manager {
	protected function prepareRequest(array $request) {
		$command = ['name' => 'test',
					'params' => ['text' => 'Hello! I am just a simple test command =)']];
		$request['commands'] = [$command];
		return parent::prepareRequest($request);
	}
}