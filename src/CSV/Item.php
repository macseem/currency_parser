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
 * @property array $currentRow
 * @property $file
 *
 */
class Item extends MainCSV{
    protected $_currentRow;
    protected $_totalCurrency;
    protected function __clone(){}
    protected function __wakeup(){}
    protected static $_instance;
    /**
     * @param null $_file
     * @return Item
     */
    public static function getInstance($_file = NULL)
    {
        if(NULL === self::$_instance){
            self::$_instance = new self($_file);
        }
        return self::$_instance;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function __set($name,$value)
    {
        $func = 'set'.ucfirst($name);
        if(method_exists($this,$func)){
            return $this->$func($value);
        }
        $prop = '_'.$name;
        return property_exists(__CLASS__,$prop)?$this->$prop=$value:false;
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
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
            catch(Exception $e){
                throw new Exception ($e->getCode(), $e->getMessage(), $e);
            }
        }
        return false;
    }

    /**
     * @return array|bool
     */
    public function nextRow(){
        parent::nextRow();
        if(empty($this->_rawRow) or false === $this->_rawRow){
            $this->_currentRow = false;
        }
        else{
            $this->_currentRow = array(
                'date' => current($this->_rawRow),
                'currency' => end($this->_rawRow),
                'isPay' => $this->isPay(),
                'sum' =>  $this->getSum(),
            );
        }
        return $this->validate();
    }

    /**
     * @return bool
     */
    private function isPay()
    {
        $count = count($this->rawRow);
        for($i=1;$i<$count-1;$i++){
            if(preg_match('/^PAY[0-9]+.*$/',$this->rawRow[$i])){
                return true;
            }
        }
    }

    /**
     * @return float|bool
     */
    private function getSum()
    {
        $count = count($this->rawRow);
        return empty($this->rawRow[$count-2])?false:$this->rawRow[$count-2];
    }
    //<editor-fold desc="Setters">
    /**
     * setCurrentRow
     * alias for nextRow()
     * @return array|bool
     */
    private function setCurrentRow()
    {
        return $this->nextRow();
    }

    /**
     * @return Total
     */
    private function setTotalCurrency(){
        return $this->_totalCurrency = Total::getInstance();
    }
    //</editor-fold>

    /**
     * @return array|bool
     */
    private function validate()
    {
        if(!is_array($this->_currentRow)){
            $this->_currentRow = false;
            return false;
        }
        $keys = array('currency','date','isPay','sum');
        foreach($keys as $key){
            if(!array_key_exists($key,$this->_currentRow)){
                $this->_currentRow = false;
                return false;
            }
        }
        if(!preg_match('/^[A-Z]{3}$/',$this->_currentRow['currency'])
            or !is_numeric($this->_currentRow['sum'])
            or gettype($this->_currentRow['isPay']) != 'boolean'
            or !preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/',$this->_currentRow['date'])
        ){
            $this->_currentRow = false;
        }
        return $this->_currentRow;
    }

}