<?php
/**
 * Clase base Qubit_Bootstrap.
 * 
 * Creado por: Alejandro Cepeda   
 * Ultima modificaión: jueves 19 de noviembre 2015
 * Mas info: Qubit alejandrocepeda25@gmail.com
 * 
 * @category    Qubit
 * @package     Qubit_Bootstrap
 */
class Qubit_Bootstrap{
    private $_controllerName = '';
    private $_actionName = '';
    private $_viewpath = '';
    private $_layoutpath = '';
    private $_layoutName = 'layout';
    private $_controllerpath = '';
    public static $_instance = null;
    
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct(){

        $config = Qubit_Config::Read('config.ini');
                
        if (isset($config->application->locale)) {
            setlocale(LC_TIME, $config->application->locale);
        }
         
        if (isset($config->application->timezone)) {
            date_default_timezone_set($config->application->timezone);
        }

        if (isset($config->application->php_error_reporting)) {
            error_reporting($config->application->php_error_reporting);
        }

        if (version_compare(PHP_VERSION, '5.2.6') === -1) {
            
            $class = new ReflectionObject($this);
            $_classResources = array();

            $classMethods = $class->getMethods();
            $methodNames  = array();

            foreach ($classMethods as $method) {
                $methodNames[] = $method->getName();
            }
        }
        else{
            $methodNames = get_class_methods($this);
        }
        
        foreach ($methodNames as $method) {

            if (5 < strlen($method) && '_init' === substr($method, 0, 5)) {
                $this->$method();
            }
        }
    }
    
    public function getControllerName() {
        return $this->_controllerName;
    }

    public function setControllerName($controller) {
        $this->_controllerName = $controller;
        
        return $this;
    }
    
    public function setControllerPath($path) {
        $this->_controllerpath = realpath($path) . '/';
        
         return $this;
    }
    
    public function setViewPath($path) {
        $this->_viewpath = realpath($path) . '/';
        
         return $this;
    }
    
    public function setLayoutPath($path) {
        $this->_layoutpath = realpath($path) . '/';
        
         return $this;
    }
    
    
    public function setActionName($action) {
       $this->_actionName = $action;
       
        return $this;
    }
    
    public function run() {
          
        $controller_full_file = $this->_controllerpath . $this->_controllerName . '_Controller.php';

        if (is_readable($controller_full_file))  {
            require $controller_full_file;
        }

        $controller_class = $this->_controllerName . '_Controller'; 
        
        $action_name = $this->_actionName;

        if (!method_exists($controller_class, $action_name) and $action_name != 'index'){
            include($this->_controllerpath . 'error_Controller.php');
            $this->_controllerName = 'error';
            $controller_class = 'error_Controller';
            $action_name = 'index';
        }
       
        $view = Qubit_View::getInstance();
        $view->setViewName($action_name);
        $view->setControllerName($this->_controllerName);
        $view->setViewpath($this->_viewpath);
        
        $layout = Qubit_Layout::getInstance();
        
        $controller = new $controller_class;
        $controller->setView($view);
        $controller->setLayout($this->_layoutName);
        $controller->setActionName($action_name);
        $controller->setControllerName($this->_controllerName);
        $controller->init();

        if (method_exists($controller_class, $action_name)){
            $controller->$action_name();
        }

        if ($controller->getNoRender()){
            
        }
        elseif ($controller->getajaxContext()){ 
            $ajax = Qubit_Ajax::getInstance();

            echo $ajax->processRequest();
        }
        else{
            if ($controller->_disableLayout != true and $layout->_disableLayout != true){  
                
                $layout->setLayoutpath($this->_viewpath . 'layout/' )
                    ->setView($view)
                    ->setLayout($controller->_layout_name)
                    ->setContent($view->render());
                    
                echo $layout->render();
            }
            else{
                echo $view->render();
            }
        }   
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
}
?>