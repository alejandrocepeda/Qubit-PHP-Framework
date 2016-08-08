<?php
/**
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
* ayudante para crear la url anterior  
*/

class Qubit_View_Helper_HistoryBack{
    
     /*   
     *  retorna un string construido con la url anterior o window.history.back  
     */
    public function HistoryBack(){
        
        if (isset($_SERVER['HTTP_REFERER']) and strlen($_SERVER['HTTP_REFERER']) > 0){
            return $_SERVER['HTTP_REFERER'];
        }
        else{
            return "javascript:window.history.back()";
        }
         
    }
}
?>
