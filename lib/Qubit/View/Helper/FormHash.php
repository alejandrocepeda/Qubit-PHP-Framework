<?php
/**
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
* ayudante para crear Input Type Hidden con un hash de seguridad 
*/

class Qubit_View_Helper_FormHash extends Qubit_Csrf {
    
    public function formhash($options = array()){

        parent::__construct($options);
        
        $this->generateHash();

        $element = new Qubit_View_Helper_FormHidden();
        $html = $element->formHidden($this->name,array('value' => $this->_hash));

        return $html;
    }
}
?>