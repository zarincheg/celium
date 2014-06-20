<?php
include_once __DIR__."/../vendor/autoload.php";
spl_autoload_register('celiumAutoload');

function celiumAutoload($class) {
	//echo "(Celium) Try to load: ".$class."\n";
	$class = str_replace('\\', '/', $class);
	$rootPath = dirname(__FILE__);

	$rootNS = substr($class, 0, 7);
	$class =  substr($class, 7);

	if($rootNS !== "Celium/")
		return false;

	$file = $rootPath.'/'.$class.'.php';

	if(file_exists($file))
		require_once $file;
	else
		throw new Exception("Required class not found: ".$class." With class file path: ".$file);

}

\Celium\Config::init(dirname(__FILE__) . '/config');

require_once(\Celium\Config::$get->path->root.'/Logging/src/main/php/Logger.php');
Logger::configure(\Celium\Config::$get->path->root.'/logger.xml');