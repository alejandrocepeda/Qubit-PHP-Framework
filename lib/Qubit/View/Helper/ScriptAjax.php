<?php

/**
 *
 * @author Alejandro Cepeda, alejandrocepeda25@gmail.com
 */
class Qubit_View_Helper_ScriptAjax{
   
    public function ScriptAjax(){
        
        //$view = Qubit_View::getInstance();
        //$view->headScript(array(type => "text/javascript",src => "js/ajax.js"));   
        
        
        /*
        echo "<script>\n";
        echo "var s = document.createElement('script');\n";
        echo "s.type = 'text/javascript';\n";
        echo "s.src = 'js/ajax.js';\n";
        echo "$('head').append(s);\n";
        echo "</script>\n";
        */
        
        return "<script src='js/ajax.js'></script>\n";
        
    }
}
?>
