<?php


class Qubit_Element_Select{
    private $_name = null;
    private $_options = null;
    private $_option = null;
    private $_params = null;
    
    function __construct($option = array()) {
    
        $this->_params = $option;
    }
    
    function __toString() {
        if (is_array($this->_params)){
            foreach($this->_params as $key => $item){
                $params.=" $key='$item'";
            }
        }
        else{
            $params.=" name='$this->_params'";
        }
        
        if (count($this->_params) > 0){
            $return= "<select " . $params . ">";
            $return.=$this->_options;
            $return.= "</select>";
        }
        else{
            $return.=$this->_options;
        }
        
         
        return $return; //$this->_options;
    }
    
    public function addOption($key,$item,$selectedId = null){
        $options = '';
        if (null == $selectedId){
            $this->_options.='<option value="' . $key .'">' . $item . '</option>' .  "\n";
        }
        else{
            $this->_options.='<option ';
            $this->_options.= ($key == $selectedId) ? 'selected':'';
            $this->_options.=' value="' . $key .'">' . $item . '</option>' . "\n"; 
        }
        return $this;
    }
   
    public function addMultiOptions($array,$selectedId = null){
        $options = '';
	   
        foreach($array as $key => $value){
           
		    if (null == $selectedId){
                $options.='<option value="' . $key .'">' . $value . '</option>' .  "\n";
            }
            else{
                $options.='<option ';
                $options.= ($key == $selectedId) ? 'selected':'';
                $options.=' value="' . $key .'">' . $value . '</option>' . "\n"; 
            }
        }
        
        $this->_options.= $options;

        return $this;
    }
}
?>

