<?php
/**
 * @author Maksim Dudkin
 * @package test
 *
 */

define('BASE_DIR', __FILE__);
define('DS',DIRECTORY_SEPARATOR);

class currency {
    private $_label;
    private $_total;
    public function __construct($label)
    {
        $this->_label = $label;
        $this->_total = 0.0;
    }
    public function addTotal($sum)
    {
        if(!is_numeric($sum)){
            throw new Exception(500, 'Sum '.$sum.' is not a number');
        }
        return $_total+=$sum;
    }
    public function getTotal(){
        return $this->total;
    }
}


class CurrencyTotal {
    private $children = array();
    protected static $_instance;

    private function __clone(){}
    private function __wakeup(){}

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if(null === self::$_instance){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function addCurrency($value)
    {
        if(isset($this->children[$value])){
            throw new Exception(500, 'There is already '.$value.' currency');
        }
        return $this->children[$value] = new currency();
    }
    
    public function getCurrency($value)
    {
        if(!isset($this->children[$value])){
            $this->addCurrency($value);
        }
        return &$this->children[$value];
    }

    public function addCurrencyTotal($currency,$sum)
    {
        try{
            $currency = $this->getCurrency($value);
            $currency->addTotal($sum);
        }
        catch(Exception $e){
            throw new Exception ($e->getCode(), $e->getMessage(), $e);
        }
        return $currency->getTotal();
    }
}

class CSV {
    protected $_text;
    protected static $_instance;
    
    private function __clone(){}
    private function __wakeup(){}
    public static function getInstance($_text = NULL)
    {
        if(NULL === self::$_instance){
            self::$_instance = new self($_text);
        }
        return self::$_instance;
    }

    public function __set($name,$value)
    {
        $func = 'set'.ucfirst($name);
        if(method_exists($func)){
            return $this->$func($value);
        }
        $prop = '_'.$name;
        return $this->$prop=$value;
    }
    protected function __construct($_text = NULL)
    {
        try{
            $this->$text=$_text;
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
    
    private function setText($_text)
    {
        if(!$this->validate($_text)){
           throw new Exception(500,'CSV format is not valid'); 
        }
        $this->_text = $_text

    }
    private function validate($_text)
    {
        return true;
    }

}

/**
 * Bootstrap
 *
 */
class Bootstrap
{
	public static function main($argv)
	{
	    $content = file_get_contents(BASE_DIR.DS.'statement.csv');
        $csv = CSV::getInstance($content);

	}
}

Bootstrap::main($argv);

