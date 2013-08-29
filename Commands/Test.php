<?php
namespace Commands;
/**
 * Description of class
 * @author Kirill Zorin <zarincheg@gmail.com>
 */

class Test extends \Command {

	public function execute($input = null)
	{
		echo "Yes sir!\n";
		return "Command success";
	}
}