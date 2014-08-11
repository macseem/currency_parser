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

        $orderBy = 'asc';
        $filePath = BASE_DIR.DS.'statement.csv';
        $totals =Total::getInstance($filePath)->getAllTotals();
        usort($totals,$orderBy == 'asc'?"self::orderAsc":"self::orderDesc");
        self::printTotals($totals);


	}
    public static function orderAsc($a, $b)
    {
        if ($a[0] == $b[0]) {
            return 0;
        }
        return ($a[0] < $b[0]) ? -1 : 1;
    }
    public static function orderDesc($a, $b)
    {
        if ($a[0] == $b[0]) {
            return 0;
        }
        return ($a[0] < $b[0]) ? 1 : -1;
    }
    protected static function printTotals(array $totals = array()) {
        foreach($totals as $total){
            echo $total[0].' '.number_format($total[1],2,'.',',').PHP_EOL;
        }
    }
}

Bootstrap::main($argv);

