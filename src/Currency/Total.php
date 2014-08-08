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

    //<editor-fold desc="Singleton code">
    private function __clone(){}
    private function __wakeup(){}

    protected function __construct($file)
    {
        $text = file_get_contents($file);
        $file = CSV::getInstance($text);
        $this->initChildren($file->rows);
    }

    private function initChildren($rows){
        foreach($rows as $row){
            //TODO:macseem: fix problem with getSum func
            var_dump($row);
            $count = count($row);
            $currency=end($row);
            $sum = prev($row);
            var_dump($currency,$sum);
            for($i=1;$i<$count-1;$i++){
                if(preg_match('/^PAY[0-9]\w\w$/',$row[$i])){
                    $this->addCurrencyTotal($currency,$sum);
                }
            }
        }

    }
    public static function getInstance($file)
    {
        if(null === self::$_instance){
            self::$_instance = new self($file);
        }
        return self::$_instance;
    }
    //</editor-fold>

    /**
     * @param $value
     * @return Child
     * @throws \Exception
     */
    public function addCurrency($value)
    {
        if(isset($this->children[$value])){
            throw new \Exception(500, 'There is already '.$value.' currency');
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

    public function addCurrencyTotal($currencyLabel,$sum)
    {
        try{
            $currency = &$this->getCurrency($currencyLabel);
            $currency->addTotal($sum);
        }
        catch(\Exception $e){
            throw new \Exception ($e->getCode(), $e->getMessage(), $e);
        }
        return $currency->getTotal();
    }

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