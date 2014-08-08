<?php
/**
 * Created by PhpStorm.
 * User: macseem
 * Date: 8/8/14
 * Time: 3:06 PM
 */


namespace Macseem\Test\Currency\Total;
/**
 * Class Library_Currency_Total
 */
class Macseem_Test_Currency_Total {
    private $children = array();
    protected static $_instance;

    //<editor-fold desc="Singleton code">
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
    //</editor-fold>

    /**
     * @param $value
     * @return Library_Currency_Item
     * @throws Exception
     */
    public function addCurrency($value)
    {
        if(isset($this->children[$value])){
            throw new Exception(500, 'There is already '.$value.' currency');
        }
        return $this->children[$value] = new Macseem_Test_Currency_Item($value);
    }

    /**
     * @param $value
     * @return Library_Currency_Item
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
        catch(Exception $e){
            throw new Exception ($e->getCode(), $e->getMessage(), $e);
        }
        return $currency->getTotal();
    }
}