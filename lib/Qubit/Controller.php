<?php
/**
 * Clase base Qubit_Controller.
 * 
 * Creado por: Alejandro Cepeda   
 * Ultima modificaión: jueves 19 de noviembre 2015
 * Mas info: Qubit alejandrocepeda25@gmail.com
 * 
 * @category    Qubit
 * @package     Qubit_Controller
 */

/*
function __autoload($className) {

    Qubit_Loader::LoadClass($className);
}
*/

class Qubit_Controller{
    private static $objects = array();
    public static $router = array();
    public $_ajaxContext = false;
    public $_disableLayout = false;
    public $_layout_name = 'layout';
    private $_baseUrl = '';    
    protected $view = null;    
    protected $_actionName = null;
    protected $_controllerName = null;
    public static $_instance = null;
    private $_helperStore = array();
    private $_noRender = false;

    function __call($name, $args) {
        // busca en la carpeta Helper del modulo para ver si existe un helper definido por el usuario
            
        $className = 'Qubit_Action_Helper_' . $name;
        
        if (!isset($this->_helperStore[$className])){    
            $file =  PATH_APP . 'application/controllers/helper/' . $name . '.php';
            if (@include($file)){
                $helper = new $className();
                if (method_exists($helper, $name)) {
                    $this->_helperStore[$className] = $helper;
                    return call_user_func_array(array($helper, $name),$args);
                }
             }
        }
        else{

            $helper =  $this->_helperStore[$className];
            return call_user_func_array(array($helper, $name),$args);
        }
    }

    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    

    public function getRequest(){
        return Qubit_Request::getInstance();
    }
    
    public function setControllerName($controller){
        $this->_controllerName = $controller;
        
        return $this;
    }

    public function getControllerName() {
        
        return $this->_controllerName;
    }
    
    public function setView($view){
        $this->view = $view;
        
        return $this;
    }
    
    public function getActionName(){
        return $this->_actionName;
    }

    public function setActionName($action){
        $this->_actionName = $action;
        
        return $this;
    }

    public function baseUrl($path = null){
        if ($path == null){
            return $this->_baseUrl;
        }
        else{
            $this->_baseUrl = $path;
            return $this;
        }
    }

    public static function setObject($object,$param_construct = null){
        include($object . '/' . $object . '.php');
        self::$objects[$object] = new $object($param_construct);
    }

    public static function getObject( $key ){
        if( is_object (self::$objects[$key])){
            return self::$objects[$key];
        }
    }
    
    public function disableLayout(){
        $this->_disableLayout = true;
    }
    
    public function setLayout($name){
        $this->_layout_name = $name;
    
        return $this;
    }

    public static function getObjects(){
        return self::$objects;
    }
    
    function __construct(){
       //
    }
    
    public function addMultiOptions($array = array(),$selectedId = null){
        $options = '';
        foreach($array as $item){
            if (null == $selectedId){
                $options.='<option value="' . current($item) .'">' . next($item) . '</option>' .  "\n";
            }
            else{
                $options.='<option ';
                $options.= (current($item) == $selectedId) ? 'selected':'';

                $options.=' value="' . current($item) .'">' . next($item) . '</option>' . "\n"; 
            }
        }
        return $options;
    }
    
    public function addOption($key,$item,$selectedId = null){
        $options = '';
        if (null == $selectedId){
            $options='<option value="' . $key .'">' . $item . '</option>' .  "\n";
        }
        else{
            $options.='<option ';
            $options.= ($key == $selectedId) ? 'selected':'';
            $options.=' value="' . $key .'">' . $item . '</option>' . "\n"; 
        }
        return $options;
    }
    

    public function forward($action,$controller = null,array $params = null){
        
        $request = Qubit_Request::getInstance();

        if (null !== $params) {
            $request->setParams($params);
        }

        $bootstrap = Bootstrap::getInstance();

        if (null == $controller) {
            $controller = $this->_controllerName;
        }
       
        $this->setActionName($action)
            ->setControllerName($controller);

        $bootstrap->setControllerName($controller)
        ->setControllerPath(PATH_APP . 'application/controllers/')
        ->setViewPath(PATH_APP . 'application/views/')
        ->setActionName($action)
        ->run();
    } 

    public function redirect($link ,$params = null){        
        
        $str = Qubit_Router::getInstance()->assemble($link ,$params);
        
        if(headers_sent()){
            print "
                <script type='text/javascript'>
                        window.location='" . $str . "';
                </script>\n";
        } else {
            header('Location: ' . $str);
        }
        exit();
    }
    
    public function getNoRender(){
        return $this->_noRender;
    }

    public function setNoRender($flag = true){
        $this->_noRender = ($flag) ? true : false;
    }

    public function ajaxContext(){
        
        if (!$this->_ajaxContext){
            Qubit_Loader::LoadClass('Qubit_Ajax');
        }
        
        $ajax = Qubit_Ajax::getInstance();
        $this->_ajaxContext = true;
        
        return $ajax;
    }

    public function getajaxContext(){
        return $this->_ajaxContext;
    }    
}
?>