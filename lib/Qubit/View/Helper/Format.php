<?php
/**
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
* ayudante para dar formato en el View
*/
class Qubit_View_Helper_Format{    
     
    public function format($value,$type = null){
        
        if ($type == 'number_format'){
            $return = number_format($value, Qubit_Locale::$NumberDecimal, Qubit_Locale::$PointDecimal,  Qubit_Locale::$ThousandSep);            
        }
        elseif ($type == 'percent'){
            $return = '%'.($value / 100);            
        }
        elseif ($type == 'money'){
            $return = Qubit_Locale::$MoneyFormat . number_format($value,Qubit_Locale::$NumberDecimal, Qubit_Locale::$PointDecimal,  Qubit_Locale::$ThousandSep);         
        }
        elseif ($type == 'date_time'){       
            $return = date(Qubit_Locale::$DateTimeFormat,strtotime($value));          
        }
        elseif ($type == 'date'){       
            $return = date(Qubit_Locale::$DateFormat,strtotime($value));          
        }
        elseif ($type == 'time'){       
            $return = date(Qubit_Locale::$TimeFormat,strtotime($value));          
        }
        elseif ($type == 'long_date'){        
           $return = strftime("%A %#d de %B",strtotime(date("Y-m-d",strtotime($value)))) . ' de ' . date("Y",strtotime($value));
        }
        else{
            $return = $value;
        }
                
        return $return; 
    }
}
?>