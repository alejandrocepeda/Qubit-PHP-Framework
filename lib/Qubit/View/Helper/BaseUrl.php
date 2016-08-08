<?php

/**
 *
 * @author Alejandro Cepeda, alejandrocepeda25@gmail.com
 */
class Qubit_View_Helper_BaseUrl {
   
    public function BaseUrl(){
        
        $base = $this->GetBasePath() . '/public/';
       
        return $base;
    }
    
    public function GetBasePath() { 
        $B=path_app; 
        $A=substr($_SERVER['DOCUMENT_ROOT'], strrpos($_SERVER['DOCUMENT_ROOT'], $_SERVER['PHP_SELF']));
        $C=substr($B,strlen($A));
        return $C; 
    } 
}
?>
