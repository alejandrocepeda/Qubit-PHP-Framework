<?php
/**
 * Clase de fragmentos SQL SELECT.
 * 
 * Ultima modifición: jueves 26 de febrero 2014     
 * Mas info: Qubit  alejandrocepeda25@gmail.com
 * 
 * @category    Qubit
 * @package     Db
 * @subpackage  Expr   
 */


class Qubit_Db_Expr{
    protected $_expression;
    
    public function __construct($expression){
        $this->_expression = (string) $expression;
    }

    public function __toString(){
        return $this->_expression;
    }
}

?>