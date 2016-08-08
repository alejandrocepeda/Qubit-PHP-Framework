<?php
/**
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
* ayudante para crear escapar texto en la vista depende del setEscape en el View
*/

class Qubit_View_Helper_Escape{
    
     
    public function escape($value){
         
        $setEscape = Qubit_View::getInstance()->getEscape();
        
        if ($setEscape == null){
            return $value;
        }
        elseif ($setEscape == 'htmlspecialchars' or $setEscape == 'htmlentities'){
            return call_user_func($setEscape, $value, ENT_COMPAT);
        }
        else{
            return $value;
        }
         
    }
}
?>
