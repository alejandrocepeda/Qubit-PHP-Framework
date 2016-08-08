<?php
/**
 *
 * @author Alejandro Cepeda, alejandrocepeda25@gmail.com
 */

class Qubit_Exception extends Exception {
    
    const EXCEPTION_NO_CONTROLLER = 'no_controller';
    const EXCEPTION_NO_ACTION = 'no_action';
    const EXCEPTION_APP_ERROR = 'exception';
    private static $_type = null;
    private static $_Exception;
    private static $_view;
    
    public function __construct($message, $code = null) {
        self::$_type = $message;
        
        if(DEBUG) {  
            self::$_view = 'exception';
        }
        else{
            self::$_view = 'index';
        }
        
        parent::__construct($message,$code,null);
    }
    
    public static function getType(){
        return self::$_type;
    }
    
    public static function getView(){
        return self::$_view;
    }
    
    public static function getError_Handler(){
        return self::$_Exception;
    }

    public static function handle_exception($e){ 
        
        self::$_Exception = $e;

        $bootstrap = Qubit_Bootstrap::getInstance();
        
        $bootstrap->setControllerName('error')
        ->setActionName('index')
        ->setControllerPath(PATH_APP . 'application/controllers/')
        ->setViewPath(PATH_APP . 'application/views/')
        ->run();
    }
}
?>
