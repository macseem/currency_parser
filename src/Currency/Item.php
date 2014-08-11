<?php
/**
 * Created by PhpStorm.
 * User: macseem
 * Date: 8/8/14
 * Time: 3:04 PM
 */

namespace Macseem\Test\Currency;
/**
 * Class Item
 */
class Item {
    private $_label;
    private $_total;
    public function __construct($label)
    {
        $this->_label = $label;
        $this->_total = 0.0;
    }
    public function addTotal($sum)
    {
        if(is_numeric($sum)){
            return $this->_total+=$sum;
        }
        throw new Exception(500, 'Can\'t add total sum. There is no numbers in row');
    }
    public function getTotal(){
        return $this->_total;
    }
    public function getLabel(){
        return $this->_label;
    }
}