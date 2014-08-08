<?php
/**
 * @author Maksim Dudkin
 * @package test
 *
 */

define('BASE_DIR', dirname(__FILE__));
define('DS',DIRECTORY_SEPARATOR);
require_once('vendor/autoload.php');
use Macseem\Test\Currency\Total;

/**
 * Bootstrap
 *
 */
class Bootstrap
{
	public static function main($argv)
	{
        $filePath = BASE_DIR.DS.'statement.csv';
        foreach(Total::getInstance($filePath)->getAllTotals() as $Total){
            echo $Total[0].' '.$Total[1];
        }


	}
}

Bootstrap::main($argv);

