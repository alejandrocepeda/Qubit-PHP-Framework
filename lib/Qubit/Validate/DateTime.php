<?php
class Qubit_Validate_DateTime{
    
    
    public function isValid($dateTime) {    
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) { 
         
            if (checkdate($matches[2], $matches[3], $matches[1])) { 
                return true; 
            } 
        } 

        return false;          
    } 
}
?>
