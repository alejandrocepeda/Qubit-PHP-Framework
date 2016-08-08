<?php

/**
 *
 * @author Alejandro Cepeda, alejandrocepeda25@gmail.com
 */


class Qubit_View_Helper_FlashMessages {
   
    public function FlashMessages(){
        
        $FlashMessenger = Qubit_FlashMessenger::getInstance();
        
        return $FlashMessenger->get();
    }
}
?>
