<?php
/**
 *
 * @author Alejandro Cepeda, alejandrocepeda25@gmail.com
 */
class Qubit_Dispatch  {
    public static $_instance = null;
    public static $_controller = null;
    public static $_actionName = null;
    public static $_controllerName = null;
    public static $_controller_class_name = null;
    public static $_controller_file_name = null;
    
    public static function execute(){
        
        
        $view = Qubit_View::getInstance()
            ->setViewName(self::$_actionName)
            ->setActionName(self::$_actionName)
            ->setControllerName(self::$_controllerName)
            ->setViewpath(path_app . '/views/' . self::$_controllerName . '/');
               
       
        $controller = new self::$_controller();
        $controller->setActionName(self::$_actionName);
        $controller->setControllerName(self::$_controllerName);
        
        $controller->view = $view;
        $controller->request = Qubit_Request::getInstance();      
        
        if (method_exists(self::$_controller_class_name,'init')){
             $controller->init();
        }
        
        
        call_user_func(array($controller, self::$_actionName));    
        
        if ($controller->getajaxContext()){
             $ajax = Qubit_Ajax::getInstance();
             echo $ajax->processRequest();
        }
        else if (!$controller->getajaxContext()){    
            if ($controller->_disableLayout != true){  
                $layout = Qubit_Layout::getInstance()   
                    ->setView($controller->view)
                    ->setLayoutName($controller->_layout_name)
                    ->setActionName(self::$_actionName)
                    ->setControllerName(self::$_controllerName)
                    ->setLayoutpath(path_app . '/views/layout/')
                    ->setContent($view->render());

                    echo $layout->render();
            }
            else{
                echo $view->render();
            }
        }
    }
    
    public static function getController(){
        return self::$_instance;
    }
    
    public static function LoadController($controller,$action){
        
        self::$_controller_class_name = $controller.'_Controller';
        self::$_controllerName = $controller;
        self::$_controller_file_name = path_app . '/controllers/' . $controller . '_Controller.php';
        self::$_actionName = $action;
        
        
      
         
        if (!file_exists(self::$_controller_file_name)){
            throw new Qubit_Exception('no_controller');
        }
        
        include (self::$_controller_file_name);
        
        if (!class_exists(self::$_controller_class_name)){
            throw new Qubit_Exception('error');
        }

        if (!method_exists(self::$_controller_class_name,self::$_actionName)){
            throw new Qubit_Exception('no_action');
        }
        
        self::$_controller = self::$_controller_class_name;
        return true;
    } 
}

?>
