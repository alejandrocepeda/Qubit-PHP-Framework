<?php
class Qubit_Validation{
    private $_isvalid = true;
    private $_return_errors = array();
    private $_template = '';
    
    public function getErrorMsgs(){
        return $this->_return_errors;
    }
    
    public function getMessages(){
        return $this->_return_errors;
    }
    
    public function setTemplate($value){
        $this->_template = $value;
    }

    public function addValidation($name,$value,$type,$msg = '',$param_arr = array()){
        $t = explode(':',$type);
        $return = false;

        if (isset($param_arr)){
            $param_arr[] = $value;
            array_unshift($param_arr,$value);
            $return = call_user_func_array(array($this,$t[0]),$param_arr);
        }
        else{
            $return = call_user_func(array($this,$type),$value) ;
        }
        
        if (!$return){
            $this->_isvalid = false;
        }	
        
        $tmp_msg = str_replace('%message%', $msg, $this->_template);
        
        $this->_return_errors[$name] = array(
            'isValid' => $return,
            'errorMsg' => $tmp_msg);
    }

    private function stringLength($value,$min,$max){
        
        if (iconv_strlen($value,'UTF-8') >= $min and iconv_strlen($value,'UTF-8') <= $max){
            return true;
        }else{
            return false;
        }
    }

    private function alpha($value,$allowSpace = true){
        $whiteSpace = $allowSpace ? '\s' : '';
        $patron = '/[^a-zA-Z' . $whiteSpace .']/';

        if (preg_match($patron, $value)) {
               return false;
        }
        else{
               return true;
        }
    }

    private function DateTime($data,$format) {
        if (date($format, strtotime($data)) == $data) {
            return true;
        }
        else{
             return false;
        }
    }

    private function alpha_numeric($value,$allowSpace = true){
        $whiteSpace = $allowSpace ? '\s' : '';
        $patron = '/[^a-zA-Z0-9' . $whiteSpace .']/';

        if (preg_match($patron, $value)) {
               return false;
        }
        else{
               return true;
        }
    }

    private function digit($value){
        $patron = "/^[[:digit:]]+$/";

        if (preg_match($patron, $value)) {
                return true;
        }
        else{
                return false;
        }
    }

    private function notEmpty($value){	
        if (!is_string($value)){
                return false;
        }

        if (strlen(trim($value)) == 0 or $value == null or $value == '0') {
                return false;
        }
        else{
                return true;
        }
    }

    private function emailaddress($value){
        if (!is_string($value)) {
            return false;
        }

        $matches = array();

        if ((strpos($value, '..') !== false) or
            (!preg_match('/^(.+)@([^@]+)$/', $value, $matches))) {
            return false;
        }
        else{
                return true;
        }
    }

    public function isValid(){
        return $this->_isvalid;
    }
}
?>