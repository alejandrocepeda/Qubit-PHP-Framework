<?php
/**
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
* ayudante para crear urls que dependan del router 
*/

class Qubit_View_Helper_Url{
    
     /*   
     *  @param optional array $link: recibe array(controller => 'controllerName',action => 'actionName') 
     *  @param optional array $params: recibe los parametros que se van a enviar por la url
     *  retorna un string construido partiendo el router definido  
     */
    public function url($link = array(),$params = array()){
        $url = Qubit_Router::getInstance()->assemble($link ,$params);

        return $url;
    }
}
?>