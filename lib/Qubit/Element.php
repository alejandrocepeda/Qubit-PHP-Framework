<?php
class Qubit_Element{
    
    public function createElement($type,$option){
        if ($type == 'select'){
            
            Qubit_Loader::LoadClass("Qubit_Element_Select");
            
            $class = new Qubit_Element_Select($option);
            return $class; 
        }
    }
    
    
}
?>
