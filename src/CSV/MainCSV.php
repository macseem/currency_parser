<?php
/**
 * Created by PhpStorm.
 * User: macseem
 * Date: 8/11/14
 * Time: 12:43 PM
 */

namespace Macseem\Test\CSV;

/**
 * Class MainCSV
 * @package Macseem\Test\CSV
 * @property array $rawRow
 */
abstract class MainCSV {
    protected $_file;
    protected $_rawRow;

    /**
     * @param stream $_file
     */
    protected function __construct($_file = NULL)
    {
        try{
            $this->_file=fopen($_file,"r");
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * __desctruct
     * closes _file handler
     */
    public function __destruct(){
        try{
            fclose($this->_file);
        }
        catch(Exception $e){

        }
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
     * @return array
     */
    public function nextRow()
    {
        while(!feof($this->_file)){
            $this->rawRow = explode(',',trim(fgets($this->_file)," \t\r\n"));
            if(!empty($this->_rawRow)){
                break;
            }
        }
        return $this->rawRow;
    }
} 