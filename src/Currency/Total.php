<?php
/**
 * Created by PhpStorm.
 * User: macseem
 * Date: 8/8/14
 * Time: 3:06 PM
 */


namespace Macseem\Test\Currency;
use Macseem\Test\Currency\Item as Child;
use Macseem\Test\CSV\Item as CSV;
/**
 * Class Total
 */
class Total {
    private $children = array();
    protected static $_instance;

    private function __clone(){}
    private function __wakeup(){}

    /**
     * __construct
     * init all children
     * performs all rows from csv file
     * @param $fileName
     */
    protected function __construct($fileName)
    {
        $file = CSV::getInstance($fileName);
        while(!feof($file->file)){
            if(is_array($file->currentRow)){
                $this->performChild($file->currentRow);
            }
            $file->nextRow();
        }
    }

    /**
     * @param $row
     */
    private function performChild($row){
        if($row['isPay']){
            $this->addCurrencyTotal($row);
        }
    }

    /**
     * @param $file
     * @return Total
     */
    public static function getInstance($file)
    {
        if(null === self::$_instance){
            self::$_instance = new self($file);
        }
        return self::$_instance;
    }

    /**
     * @param $value
     * @return Child
     * @throws Exception
     */
    public function addCurrency($value)
    {
        if(isset($this->children[$value])){
            throw new Exception(500, 'There is already '.$value.' currency');
        }
        return $this->children[$value] = new Child($value);
    }

    /**
     * @param $value
     * @return Child
     */
    public function getCurrency($value)
    {
        if(!isset($this->children[$value])){
            $this->addCurrency($value);
        }
        return $this->children[$value];
    }

    /**
     * @param $row
     * @return float
     * @throws Exception
     */
    public function addCurrencyTotal($row)
    {
        try{
            $currency = $this->getCurrency($row['currency']);
            $currency->addTotal($row['sum']);
        }
        catch(Exception $e){
            throw new Exception ($e->getCode(), $e->getMessage(), $e);
        }
        return $currency->getTotal();
    }

    /**
     * getAllTotals
     * The main function
     * @return array contains all currencies and their payed totals
     */
    public function getAllTotals()
    {
        $totals = array();
        /* @var Child $child */
        foreach($this->children as $child){
            $totals[]=array(
                $child->getLabel(),
                $child->getTotal()
            );
        }
        return $totals;
    }
}