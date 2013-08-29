<?php
spl_autoload_register('celium_autoload');

function celium_autoload($class) {
	$path = str_replace('\\', '/', $class).'.php';
	$dirname = dirname(__FILE__).'/';

	//@todo Чтото сделать с этим бредом
	if(preg_match('!^Logger.*?!', $path)) {
		return true;
	}

	if(file_exists($dirname.$path))
		require_once $dirname.$path;
	else
		throw new Exception("Required class not found: ".$class."\nWith class file path: ".$dirname.$path);
}

\Configure::init(dirname(__FILE__) . '/config');

require_once(\Configure::$get->path->root.'/Logging/src/main/php/Logger.php');
Logger::configure(\Configure::$get->path->root.'/logger.xml');