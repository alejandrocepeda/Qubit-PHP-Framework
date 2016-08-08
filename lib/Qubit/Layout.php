<?php
class Qubit_Layout{
    private $_layout_name = 'layout';
    private $_layout_format = '.php';
    private $_baseUrl = '';
    private $_path_layout = '';
    private $_view_content = '';
    public $_disableLayout = false;
    public $content = '';
    public $_controllerName = '';
    public $_actionName = '';
    private $view = null;
    public static $_instance = null;

    public function disableLayout(){
        $this->_disableLayout = true;
    }
    
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
 
    function __construct(){
       // 
    }
    
   
    
    public function setView($view){
        
        if (!$view instanceof Qubit_View){
            throw new Qubit_Exception('La vista especifica en setView no es valida.');
        }
        
        $this->view = $view;
        
        $reflect = new ReflectionObject($this->view);
        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC );
        
        foreach ($props as $prop) {
            $name = $prop->getName(); 
            
            //echo $name;
            // solo propiedades ()
            //if ($props->isStatic){
                //$this->$name = $this->view$name;
            //}
            //else{
                @$this->$name = $this->view->$name;
            //}
        }
        
        return $this;
    }
    
     public function setContent($content){
        $this->_view_content = $content;

        return $this;
    }
    
    public function getContent(){
        return $this->_view_content;
    }

    public function setLayoutFormat($format){
        $this->_layout_format = $format;

        return $this;
    }

    public function setLayout($name){
        
        $this->_layout_name = $name;
        
        return $this;
    }

    public function setLayoutpath($path){
        $this->_path_layout = $path;

        return $this;
    }


    public function setActionName($name){
        $this->_actionName = $name; 

        return $this;
    }

    public function setControllerName($name){
        $this->_controllerName = $name; 

        return $this;
    }
    /*
    public function baseUrl($path = null){
         if ($path == null){
             return $this->_baseUrl;
         }
         else{
             $this->_baseUrl = $path;
             return $this;
         }

     }
     * 
     */

     public function getLayoutName(){
        return $this->_layout_name;
     }

     function render($name = null) {
         
        if ($name == null){
            $name = $this->_layout_name;
        }

        if(!$salida = $this->file_get_conten($this->_path_layout . $name . $this->_layout_format)){
            throw new Qubit_Exception('El layout especifico en setLayout no es valida.');
        }
        else{
            return $salida;
        }
     }

     private function file_get_conten($file){
         ob_start();
         if (!@include_once($file)){
             return false;
         }
         else{
             $salida = ob_get_contents(); 
         }
         ob_end_clean(); 
         return $salida; 
     }

     public function url($link ,$params = array()){
         $url = Qubit_Router::getInstance()->assemble($link ,$params);
         return $url;
         
     }
}
?>