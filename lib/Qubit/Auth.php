<?php
/**
 * Clase base Qubit_Auth.
 * 
 * Creado por: Alejandro Cepeda   
 * Ultima modificaión: sabado 13 de febrero 2016
 * Mas info: Qubit alejandrocepeda25@gmail.com
 * 
 * @category    Qubit
 * @package     Qubit_Auth
 */

class Qubit_Auth{
	private $_table_name = '';
	private $_where = '';
	private $_username_colum = '';
	private $_userpass_colum = '';
	private $_username_value = '';
	private $_userpass_value = '';
	private $_isok = false;
	private $_db = null;
	private $_SecurityNameFunction = '';
	private $_ResultRows = array();
	private $_ResultRowsValues = array();
	private $_session_storage = null;
	private $_hasIdentity = null;
	private $_hasIdentityCodeSecret = 'f4b68c6b18430bc4fac6730dfc856736422a2755';
        
    public static $_instance = null;
    
    public static function getInstance(){
       if (null === self::$_instance) {
           self::$_instance = new self();
       }

       return self::$_instance;
    }

    public function setStorage($storage){
        $this->_session_storage = $storage;
        
        return $this;
    }
	/*
	@param $value cadena de los criterios Ej: bloqueado = 0 AND supervisor = 1
	*/
	public function setWhere($value){
		$this->_where = $value;
                
                return $this;
	}
	/*
	@param $link db instancia
	optional
	*/
	public function setDbAdapter($link){
		$this->_db = $link;
                
                return $this;
	}
	/*
	@param $name nombre de la tabla de usuarios
	*/
	public function setTable($name){
		$this->_table_name = $name;
                
                return $this;
	}
	/*
	@param $username_colum nombre de la columna donde esta el nombre de usuario
	@param $userpass_colum nombre de la columna donde esta el pass de usuario
	*/
	public function setColumCredential($username_colum,$userpass_colum){
		$this->_username_colum = $username_colum;
		$this->_userpass_colum = $userpass_colum;
                
                return $this;
	}
	/*
	@param $username_value valor del nombre de usuario escrito por el usuario
	@param $userpass_value valor del pass escrito por el usuario
	*/
	public function setValueCredential($username_value,$userpass_value){
		$this->_username_value = $this->cleanMagicQuotes($username_value);
		$this->_userpass_value = $this->cleanMagicQuotes($userpass_value);
                
                return $this;
	}
	/*
	funcion que retorna la validacion ejecutada por el function authenticate();
	*/
	public function IsOk(){
		return $this->_isok;
	}
	
	protected function cleanMagicQuotes($value){
            return stripslashes($value);
        }
	
	/*
	@param $value valur que ser codifigado
	funcion que retorna el valor a ser codificado
	*/
	private function Security($value){
            if (strlen($this->_SecurityNameFunction) == 0){
                return $value; 
            }
            else{
                $funcname = $this->_SecurityNameFunction;
                return $funcname($value);
            }
	}
	
	public function setSecurity($value){
		return $this->_SecurityNameFunction = $value; 
	}
	
	public function setResultRows($rows){
            if (is_array($rows)){
                $this->_ResultRows = $rows;
            }
	}
	
    public function isValid(){
        
        $_isvalid = $this->_session_storage->getSession($this->_hasIdentityCodeSecret);
        
        if (isset($_isvalid)){
            return true;
        }
        else{
            return false;
        }
        
        return true;

    }

    public function hasIdentity(){
        return $this->_hasIdentity;
    }

    public function SaveIdentity(){

        if ($this->_isok){
            
            $_hasIdentity = null;
            
            $a = $this->_ResultRowsValues;

            foreach ($a as $key => $value){

                $this->_session_storage->setSession($key,$value);
                $_hasIdentity.=$value;
            }
            
            $this->_hasIdentity = SHA1($_hasIdentity);
            $this->_session_storage->setSession($this->_hasIdentityCodeSecret,$this->_hasIdentity);
        }
	}
	
    public function clearIdentity(){
        $this->_session_storage->Logout();   
    }

	public function authenticate(){
		
            if (strlen($this->_username_value) == 0 
                or strlen($this->_userpass_value) == 0
                or strlen($this->_username_colum) == 0
                or strlen($this->_userpass_colum) == 0){

                $this->_isok = false;
            }
            else{		
                if ($this->_db == null){
                    // si no se especifico ningun db adapter en setDbAdapter (instancia de la class db)
                    // se obtiene la instancia guardada en el core
                    $db = Qubit_Db::getAdapter();
                }
                else{
                    $db = $this->_db;
                }

                $s = '';

                foreach ($this->_ResultRows as $row){
                    $s = (strlen($s) == 0) ? $s = $row : $s = $s . ',' . $row;
                }

                $where = (strlen($this->_where) == 0) ? '' : $this->_where . ' AND '; 

                $query = 'SELECT ' . $s . ' ' . 
                                ' FROM ' . $this->_table_name .  
                                ' WHERE ' . $where . ' ' . $this->_username_colum . ' = "' . $this->_username_value . '"' .
                                ' AND ' . $this->_userpass_colum . ' = "' . $this->Security($this->_userpass_value) . '"';
                $rst = $db->getRow($query);

                if ($rst != null){

                    $this->_ResultRowsValues = $rst;
                    $this->_isok = true;
                }
            }
	}
}

?>