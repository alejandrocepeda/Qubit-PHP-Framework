<?php
/**
 * Clase base Qubit_Session.
 * 
 * Creado por: Alejandro Cepeda   
 * Ultima modificaión: sabado 13 de febrero 2016
 * Mas info: Qubit alejandrocepeda25@gmail.com
 * 
 * @category    Qubit
 * @package     Qubit_Session
 */
 
class Qubit_Session{
    public static $_instance = null;
    
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function sessionExists($key){
        return isset($_SESSION[$key]);
    }

    public function unsetSession($key){
        unset($_SESSION[$key]);
    }

    public function setSession($key,$value){
        $_SESSION[$key] = $value;

        return $this;
    }

    public function getSession($key,$default = false){
        return $_SESSION[$key];
    }
    
    public function StartIsok(){
        if (isset($_SESSION['StartIsOk'])){
            if ("on" == $_SESSION['StartIsOk']) {
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    
    function __construct(){
        $this->Start();
    }

    public function Start(){
        session_start();
        ob_start();
        
        $_SESSION['StartIsOk'] = 'on';
    }

    public function Logout(){
        $_SESSION['StartIsOk'] = null;
        unset($_SESSION['StartIsOk']);
       
        @session_unset();
        @session_destroy();
        @session_regenerate_id(true);  
    }
}
?>