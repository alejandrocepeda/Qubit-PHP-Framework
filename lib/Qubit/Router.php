<?php
/*
	class.router.php script mapea las entradas url para determinar que modulo controlador y accion se deben ejecutar  
	Creado por: Alejandro Cepeda				     
	Ultima modificai�n: lunes 01 de octubre 2012	 
	Mas info: alejandrocepeda25@gmail.com			 												
*/
class Qubit_Router{
    private $_router = array();
    private $_request;
    private $_querystring_pieces = array();
    private $_url_separator = '/';
    public static $_instance = null;
    private $_params = array();
        
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function addRoute($route,$value = null){
        if (is_string($route)){
            $this->_params[$route] = $value;    
        }
        elseif(is_array($route)){
            foreach($route as $key => $val){
                $this->_params[$key] = $val;
            }
        }
    }

    public function assemble($link ,$params = array()){
        
        // si es una url de tipo http://www.dominio.com 
        if (!is_array($link)){
            if (is_string($link)){
                if (preg_match('/^(http(s)?:\/\/)?(www\.)?[0-9A-Za-z]+(\.)+(com)+((\.|\/|\?=)+[0-9A-Za-z]+)?/', $link)) {
                    return $link;
                }
            }
        }

        // si no existe el elemento controller se usa el controller actual
        if (!isset($link['controller'])) {
            $link['controller'] = Qubit_Controller::getInstance()->getControllerName();
        }
        
        $url.= $link['controller'];
        
        // si no existe el elemento action se omite
        if (isset($link['action'])) {
            $url.= '?action='.$link['action'];
        }
        
        if (empty($params)){
            if (is_array($link['query']) and !empty($link['query'])){
                $params = $link['query'];
            }
        }

        if (!empty($this->_params)){
            $params = array_merge($this->_params,$params);
        }

        if (is_array($params)){
            if ($params != null){        
                
                if (!isset($link['action'])) {
                    $url.= "?";
                }
                foreach($params as $key => $value){
                    $url.= "&$key=$value";
                }
            }
        }

        return $url;
    }    
    /*
    public function assemble($link ,$params = array()){
        $string = '';
     
        // si es una url de tipo http://www.dominio.com 
        if (!is_array($link)){
            if (is_string($link)){
                if (preg_match('/^(http(s)?:\/\/)?(www\.)?[0-9A-Za-z]+(\.)+(com)+((\.|\/|\?=)+[0-9A-Za-z]+)?/', $link)) {
                    return $link;
                }
            }
        }
        
        // si no existe el elemento controller se usa el controller actual
        if (!isset($link['controller'])) {
            $link['controller'] = Qubit_Dispatch::$_controllerName; 
        }
        // si no existe el elemento action se usa el action actual
        if (!isset($link['action'])) {
            $link['action'] = 'index'; //Qubit_Dispatch::$_actionName; 
        }
        
        // agrego como parametro en $params todo aquel elemento que sea distinto de controller y action en array $link
        foreach($link as $key => $value){
            if ($key != 'controller' and $key != 'action'){
                $params[$key] = $value;
            }
        }
        
        // contruye la cadena de parametros tipo key/value
        if (is_array($params)){
            if ($params != null){
                foreach($params as $clave => $valor){
                    $string.= "$clave/$valor/";
                }
            }
        }
      
        $arry_router = $this->getRouter();
        $link = (object)$link;
        
        foreach($arry_router as $key => $value){
            
            if (array_key_exists($key,$link)){
                //if (($link->$key) != 'index'){
                    $str.= $link->$key . $this->_url_separator;
                //}
            }
            else{
                //if (($value) != 'index'){
                    $str.= $value . $this->_url_separator;
                //}    
            }
        } 
        
        
        
        $str.= $string; 
        
        return   $this->GetBasePath() . $this->_url_separator . $str;
    }
    */

    /*
    private function DeleteLastSlah($querystring){
        if (!empty($querystring)){
            $match = substr($querystring,(strlen($querystring)-1),1);
            if (isset($match) ){
                if ($match == $this->_url_separator){
                    $s = substr($querystring,0,strlen($querystring)-1);
                    return $s;
                }
            }
        }
       
        return $querystring;
    }
    
     public function GetBasePath() { 
        $B=path_app; 
        $A=substr($_SERVER['DOCUMENT_ROOT'], strrpos($_SERVER['DOCUMENT_ROOT'], $_SERVER['PHP_SELF']));
        $C=substr($B,strlen($A));
        return $C; 
    } 
    
    function __construct() {
       
        $request = Qubit_Request::getInstance();
        
        $this->_request = $request;
        $querystring = $this->_request->querystring; 
        
        $s = $this->DeleteLastSlah($querystring);
        $q_pieces = explode($this->_url_separator,$s);   
       
        if (!empty($s)){     
            $this->_querystring_pieces = $q_pieces;
        }
        
        $this->addRouter('default',array(controller=>'index',action=>'index'));
    }
    
    public function addRouter($name ,$params){
        if (is_array($params)){
            
            if (count($this->_querystring_pieces) == 0 or empty($this->_querystring_pieces)){
                $item = 0;
                foreach($params as $key => $value){
                    $this->_router[$name][$key] = $value;
                    $item++;
                }
            }
            else{
                
                
                
                
                $array_temp = $this->_querystring_pieces;
                $p_count = count($this->_querystring_pieces);
                
                $item=0;
                
                foreach($params as $key => $value){
                    if ($p_count >= $item+1){
                        $this->_router[$name][$key] = $array_temp[$item];
                    }
                    else{
                        $this->_router[$name][$key] = $value;
                    }
                    $item++;
                }
            }
        }
        else{
            throw new Qubit_Exception('Los parametros para este router no es un array');
        }
    }
   
    public function getRouter($name = 'default'){
        return (object) $this->_router[$name];
    }
    */
}
?>