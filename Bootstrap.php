<?php
/**
 * @author Maksim Dudkin
 * @package test
 *
 */

define('BASE_DIR', dirname(__FILE__));
define('DS',DIRECTORY_SEPARATOR);
require_once('vendor/autoload.php');
use Macseem\Test\CSV\Item;

/**
 * Bootstrap
 *
 */
class Bootstrap
{
	public static function main($argv)
	{
	    $content = file_get_contents(BASE_DIR.DS.'statement.csv');
        $csv = Item::getInstance($content);


	}
}

Bootstrap::main($argv);

