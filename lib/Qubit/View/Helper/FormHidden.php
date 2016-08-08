<?php
/**
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
* ayudante para crear input type hideen 
*/

class Qubit_View_Helper_FormHidden{
    
    public function formhidden($name,array $opcions){
       
        foreach($opcions as $key => $item){
            $attribs.= $key . '="' . $item . '" ';
        }
            
        $html = '<input id="' . $name . '" name="' . $name . '" type="hidden" ' . $attribs . ' >';

    	return $html;
    }
}
?>