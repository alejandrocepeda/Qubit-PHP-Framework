<?php
/** 
 * @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
 * miercoles, 29 de octubre de 2014
 * clase base para manejar vistas 
 */
class Qubit_View{
    public static $_instance = null;
    private $_view_name = '';
    private $_view_format = '.php';
    private $_path_view = '';
    private $_base_path_view = '';
    private $_viewDefaultTemplate = 'viewDefaultTemplate.php';
    private $_controllerName = '';
    private $_actionName = '';
    private $_helperStore = array();
    private $_setEscape = 'htmlspecialchars';
    private $_helperDataBase = array(
        'doctype',
        'jquery',
        'baseurl',
        'escape',
        'fileinfo',
        'format',
        'headtitle',
        'headlink',
        'headmeta',
        'headscript',
        'scriptajax',
        'historyback',
        'url',
        'formcheckbox',
        'formradio',
        'formhidden',
        'formhash',
        'flashmessages');
    
    /**
    * Asigna variables al script de vista a través de diferentes estrategias.
    * @param  [type] $spec  [description]
    * @param  [type] $value [description]
    * @return [type]        [description]
    */
    public function assign($spec,$value = null){

        if (is_string($spec)) {
            if ('_' == substr($spec, 0, 1)) {
                return 'Miembros privados o protegidos de la clase no está permitido';
            }
            $this->$spec = $value;
        }
        elseif (is_array($spec)) {
            foreach ($spec as $key => $val) {
                if ('_' == substr($key, 0, 1)) {
                    $error = true;
                    break;
                }
                $this->$key = $val;
            }    
        }

        return $this;
    }

    public function redirect($url){
       header('Location: ' . $url);
    }
    
    public function getHost(){  
  
       $domain = $this->getServer('HTTP_HOST'); 
       $host = $this->getScheme() . "://" . $domain ;  
    
        return $host;  
    }  

    public function getCurrentUrl(){  
        $domain = $this->getServer('HTTP_HOST'); 
        $url = $this->getScheme() . "://" . $domain . $this->getServer('REQUEST_URI');  
        
        return $url;  
    }  
    
    public function setEscape($set){
        $this->_setEscape = $set;
        return $this;
    }
    
    public function getEscape(){
        return $this->_setEscape;
    }
    
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
            
        }

        return self::$_instance;
    }
    /*
     * @param string $name: nombre del helper view solicitado
     * retorna el objeto de la clase helper view cargado
     */
    public function getHelper($name){
        
        $prefix = 'Qubit_View_Helper_';
        $name = $prefix . ucwords($name);

        if (!isset($this->_helperStore[$name])){    
            

            Qubit_Loader::LoadClass($name);

            $this->_helperStore[$name]= new $name();

            return $this->_helperStore[$name];
        }
        else{
            return $this->_helperStore[$name];
        }
    } 
    
    /*
     * cuando se solicita un objeto que no existe en el view este metodo magico
     * verifica si existe un helper con dicho metodo y lo carga.
     */
    function __call($name, $args) {
         
        if (in_array(strtolower($name) , $this->_helperDataBase)){

            $helper = $this->getHelper($name);

            if (method_exists($helper, $name)) {
                return call_user_func_array(array($helper, $name),$args);
            }
        }
        else{
            // busca en la carpeta Helper del modulo para ver si existe un helper definido por el usuario
            
            $className = 'Qubit_View_Helper_' . $name;
            
            if (!isset($this->_helperStore[$className])){    
                $file =  $this->_path_view . 'helper/' . $name . '.php';

                if (include($file)){
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
    }
    
    /*
     * @param string $forma: define la extension utilizada para las vistas por defecto es .php
     * retorna la misma clase con $this
     */
    public function setViewFormat($format){
        $this->_view_format = $format;
        
        return $this;
    }
    /*
     * @param string $name: define el nombre del archivo de la vista sin su extension (ViewFormat)
     * retorna la misma clase con $this
     */
    public function setViewName($name){
        $this->_view_name = $name;
        
        return $this;
    }
    /*
     * @param string $path: define la ruta donde la clase buscara los archivos de las vistas
     * retorna la misma clase con $this
     */
    public function setViewpath($path){
        $this->_path_view = $path;
        $this->_base_path_view = dirname($path);
        
        return $this;
    }
    
    /*
     * @param string $name: define el nombre de la accion actual
     * retorna la misma clase con $this
     * este metodo no se quiere, se se utiliza la clase sola. sin el MVC
     */
    public function setActionName($name){
        $this->_actionName = $name; 
        
        return $this;
    }
     /*
     * @param string $name: define el nombre de la controlador actual
     * retorna la misma clase con $this
     * este metodo no se quiere, se se utiliza la clase sola. sin el MVC
     */
    public function setControllerName($name){
        $this->_controllerName = $name; 
        
        return $this;
    }

    public function getControllerName(){
        return $this->_controllerName; 
        
    }
    
    /*
     * retorna el nombre de la vista actual previamente definido con setViewName
     */
    public function getViewName(){
        return $this->_view_name;
    }
    
    
    /*
     * @param string optional $name: nombre de la vista que se va renderizar
     * si no se especifica el parametro $name se renderizara la vista definida con setViewName
     */
    public function render($name = null) {
        
        if ($name == null){
            $name = $this->_view_name;
        }    
        
        if(!$salida = $this->file_get_conten($this->_path_view . $this->_controllerName . '/' . $name . $this->_view_format)){
            return 'Error Cargando la Vista ' . $this->_path_view . $this->_controllerName . '/' . $name . $this->_view_format;
        }
        else{     
            return $salida;
        }
    }
    /*
     * @param string optional $file: nombre del archivo
     * retorna el contenido del archivo especificado
     * este metodo funciona como encapsulamiento del la funcion ob_get_contents() de php
     */
    protected function file_get_conten($file){
        ob_start();
        if (!@include_once($file)){
            //return false;
            throw new Qubit_Exception('No existe la vista ' . $file);
        }
        else{
            $salida = ob_get_contents(); 
        }
        ob_end_clean(); 
        return $salida; 
    }
    
    
}
?>