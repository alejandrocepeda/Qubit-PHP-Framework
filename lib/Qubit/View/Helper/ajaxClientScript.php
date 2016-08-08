<?php

/**
 *
 * @author Alejandro Cepeda, alejandrocepeda25@gmail.com
 */
/*
public function ajaxClientScript(){
        echo "<script>\n";
        echo "var s = document.createElement('script');\n";
        echo "s.type = 'text/javascript';\n";
        echo "s.src = 'js/ajax.js';\n";
        echo "$('head').append(s);\n";
        echo "</script>\n";
    }
  */  
    
class Qubit_View_Helper_ajaxClientScript {
   
    public function ajaxClientScript(){
        
        echo "<script>\n";
        echo "var s = document.createElement('script');\n";
        echo "s.type = 'text/javascript';\n";
        echo "s.src = 'js/ajax.js';\n";
        echo "$('head').append(s);\n";
        echo "</script>\n";
    }
    
   
}
?>
