<?php
/**                                                   
 * Clase para la conexión a bases de datos SQL y realizar operaciones comunes.
 * 
 * Creado por: Alejandro Cepeda                     
 * Ultima modifición: domingo 1 de marzo 2015
 * Mas info: alejandrocepeda25@gmail.com    
 * 
 * @category    Qubit
 * @package     Qubit_Db
 * @subpackage  Adapter 
 * @copyright   Qubit  alejandrocepeda25@gmail.com                                                      
 */

abstract class Qubit_Db_Adapter_Abstract{
	
	protected $_connection = null;

	protected $_numericDataTypes = array(
        Qubit_Db::INT_TYPE    => Qubit_Db::INT_TYPE,
        Qubit_Db::BIGINT_TYPE => Qubit_Db::BIGINT_TYPE,
        Qubit_Db::FLOAT_TYPE  => Qubit_Db::FLOAT_TYPE
    );

	public function getConection(){
        return $this->_connection;
    }

	public function quoteTable($name){
            return '`' . $name . '`';
    }

	public function quote($value, $type = null){
        if ($type !== null && array_key_exists($type = strtoupper($type), $this->_numericDataTypes)) {
            switch ($this->_numericDataTypes[$type]) {
                case Qubit_Db::INT_TYPE: // 32-bit integer
                    $quotedValue = (string) intval($value);
                    break;
                case Qubit_Db::BIGINT_TYPE: // 64-bit integer
                    // ANSI SQL-style hex literals (e.g. x'[\dA-F]+')
                    // are not supported here, because these are string
                    // literals, not numeric literals.
                    if (preg_match('/^(
                          [+-]?                  # optional sign
                          (?:
                            0[Xx][\da-fA-F]+     # ODBC-style hexadecimal
                            |\d+                 # decimal or octal, or MySQL ZEROFILL decimal
                            (?:[eE][+-]?\d+)?    # optional exponent on decimals or octals
                          )
                        )/x',
                        (string) $value, $matches)) {
                        $quotedValue = $matches[1];
                    }
                    break;
                case Zend_Db::FLOAT_TYPE: // float or decimal
                    $quotedValue = sprintf('%F', $value);
            }
            return $quotedValue;
        }          

        return $this->_quote($value); 
   }

    protected function _quote($value){
        if (is_int($value)) {
            return $value;
        } elseif (is_float($value)) {
            return sprintf('%F', $value);
        }
        return "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
    }
}
?>