<?php
/**
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
* ayudante para crear Input Type Checkbox 
*/

class Qubit_View_Helper_FormCheckbox{
    
    public function formcheckbox($name = null,array $opcions){
        if (array_key_exists('checkedValue', $opcions)) {
            $checkedOptions = $this->determineCheck($opcions['checkedValue']);

            unset($opcions['checkedValue']);
        }

        foreach($opcions as $key => $item){
            $attribs.= $key . '="' . $item . '" ';
        }
            
        $html = '<input id="' . $name . '" name="' . $name . '" type="checkbox" ' . $attribs . ' ' . $checkedOptions .  '>';

    	return $html;
    }

    public function determineCheck($value){
        if ($value == 1 or $value == true) {
            return ' checked ';
        }
    } 
}
?>