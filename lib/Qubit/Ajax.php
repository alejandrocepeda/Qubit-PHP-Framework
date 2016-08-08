<?php
class Qubit_Ajax {
    private $_aCommand = array();
    private $_jqueryHandler = '$';
    public static $_instance = null;

    public function setJQueryHandler($value){
        $this->_jqueryHandler = $value;
    }

    public function getJQueryHandler(){
        return $this->_jqueryHandler;
    }

    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    public function debug($value){
        $this->_aCommand[] = array('cmd' => 'debug','value' => $value);
    }

    public function log($value){
        $this->_aCommand[] = array('cmd' => 'log','value' => $value);

        return $this;
    }

    public function assign($name,$pro,$value){
        $this->_aCommand[] = array('cmd' => 'as','name' => $name,'pro' => $pro,'value' => $value);

        return $this;
    }

    public function append($name,$value,$effect = null){
       
        if ($effect != null){
            $eval = "var new_element = " . $this->_jqueryHandler . "('".$value."').hide();";
            $eval.= $this->_jqueryHandler . "('#" . $name . "').append(new_element);";

            if ($effect == 'slideDown'){
                
                $eval.="(new_element).slideDown();";
            }
            else{
                $eval.= $this->_jqueryHandler . "(new_element).show();";
            }

             $this->script($eval);
        }
        else{
            
            $this->script($this->_jqueryHandler . "('#" . $name . "').append('" . $value . "');");
        }
        
        return $this;
    }
    
    public function prepend($name,$value,$effect = null){
       
        if ($effect != null){
            $eval = "var new_element = " . $this->_jqueryHandler . "('".$value."').hide();";
            $eval.= $this->_jqueryHandler . "('#" . $name . "').prepend(new_element);";
            
            if ($effect == 'slideDown'){
                
                $eval.="(new_element).slideDown();";
            }
            else{
                $eval.= $this->_jqueryHandler . "(new_element).show();";
            }

             $this->script($eval);
        }
        else{
            $this->script($this->_jqueryHandler . "('#" . $name . "').prepend('" . $value . "');");
        }

        return $this;
    }

    public function remove($name){
        $this->script($this->_jqueryHandler . "('#" . $name . "').remove();");

        return $this;
    }

    public function submit($name,$action = null){
        if (!$action == null){
            $this->script($this->_jqueryHandler . "('#" . $name . "').attr('action','". $action ."');");
        }
        $this->script($this->_jqueryHandler . "('#" . $name . "').submit();");

        return $this;
    }

    public function alert($value){
        $this->script('alert("' . $value . '")');

        return $this;
    }

    public function redirect($value){
        $this->script('window.location = "' . $value . '"; return false;');

        return $this;
    }

    public function script($name){
        $this->_aCommand[] = array('cmd' =>'js', 'name' => $name); 

        return $this;
    }

    public function call($name,$param = null){
        if (!$param == null){
            if (is_array($param)){
                $values = '';
                foreach($param as $key => $val){
                    $datatype = gettype($val);
                    if ($datatype == 'string'){
                        $val = "'".$val."'";
                    }
                    if (strlen($values) == 0){
                        $values= "$val";
                    }
                    else{
                        $values.= ",$val";
                    }
                }
            }
            else{
                $datatype = gettype($val);
                if ($datatype == 'string'){
                    $values =  "'".$param."'";
                }
                else{
                    $values =  $param;
                }
            }
            $this->_aCommand[] = array('cmd' =>'js', 'name' => $name. '(' . $values . ')'); 
        }
        else{
            $this->_aCommand[] = array('cmd' =>'js', 'name' => $name. '()'); 
        }
    }

    public function processRequest(){
        $data['data'] = $this->_aCommand;
        return json_encode($data);
    }
}
?>

