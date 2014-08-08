<?php
/**
 * Created by PhpStorm.
 * User: macseem
 * Date: 8/8/14
 * Time: 3:08 PM
 */

namespace Macseem\Test\CSV;
/**
 * Class Item
 */
class Item {
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
        if(method_exists($this,$func)){
            return $this->$func($value);
        }
        $prop = '_'.$name;
        return $this->$prop=$value;
    }
    protected function __construct($_text = NULL)
    {
        try{
            $this->text=$_text;
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
        $this->_text = $_text;

    }
    private function validate($_text)
    {
        return true;
    }

}