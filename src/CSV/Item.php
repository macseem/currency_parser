<?php
/**
 * Created by PhpStorm.
 * User: macseem
 * Date: 8/8/14
 * Time: 3:08 PM
 */

namespace Macseem\Test\CSV;
use Macseem\Test\Currency\Total;
/**
 * Class Item
 * @property string $text
 * @property array $rows
 */
class Item {
    protected $_text;
    protected $_rows;
    protected $_totalCurrency;
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
        return property_exists(__CLASS__,$prop)?$this->$prop=$value:false;
    }

    public function __get($name){
        $_name = '_'.$name;
        if(!empty($this->$_name)){
            return $this->$_name;
        }
        $setter = 'set'.ucfirst($name);
        if(method_exists($this,$setter)){
            try{
                return $this->$setter();
            }
            catch(\Exception $e){
                throw new \Exception ($e->getCode(), $e->getMessage(), $e);
            }
        }
        return false;
    }

    protected function __construct($_text = NULL)
    {
        try{
            $this->text=trim($_text," \n\r\t");
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    //<editor-fold desc="Setters">
    private function setText($_text)
    {
        if(!$this->validate($_text)){
            throw new \Exception(500,'CSV format is not valid');
        }
        $this->_text = $_text;

    }

    private function setRows(){
        $rows = explode("\n",$this->text);
        foreach($rows as &$row){
            $row = explode(',',$row);
        }
        return $this->_rows = $rows;
    }

    private function setTotalCurrency(){
        return $this->_totalCurrency = Total::getInstance();
    }
    //</editor-fold>
    private function validate($_text)
    {
        return true;
    }

}