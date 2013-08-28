<?php
spl_autoload_register('celium_autoload');

function celium_autoload($class) {
	$path = str_replace('\\', '/', $class).'.php';

	if(file_exists(dirname(__FILE__).$path))
		require_once dirname(__FILE__).$path;
	else
		throw new Exception("Required class not found: ".$class."\nWith class file path: ".$path);
}

\Configure::init(dirname(__FILE__) . '/config');

require_once(\Configure::$get->path->root.'/Logging/src/main/php/Logger.php');
Logger::configure(\Configure::$get->path->root.'/logger.xml');