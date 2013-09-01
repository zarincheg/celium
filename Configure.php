<?php
namespace Celium;
/**
 *
 */
class Configure {

	/**
	 * @var string Config data array
	 */
	public static $get;

	public static function init($file) {
		$config = file_get_contents($file);
		self::$get = json_decode($config);

		if(!self::$get)
			throw new \Exception('Json parsing error');
	}
}
