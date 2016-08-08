<?php
class Qubit_FlashMessenger {
	public static $_instance = null;
    protected $_messages = array();    
    protected $classes = array(
    	'info'     => '<div style="position:relative;top:50px" class="alert alert-info">%s</div>',
    	'danger'   => '<div style="position:relative;top:50px" class="alert alert-danger">%s</div>',
    	'success'  => '<div style="position:relative;top:50px" class="alert alert-success">%s</div>',
    	'warning'  => '<div style="position:relative;top:50px" class="alert alert-warning">%s</div>',
        'default'  => '<div style="position:relative;top:50px" class="alert alert-default">%s</div>'
    	);

    public static function getInstance(){
	    if (null === self::$_instance) {
	        self::$_instance = new self();
	    }

	    return self::$_instance;
    }

    public function add($value,$class = 'default'){
   
    	$markup = sprintf($this->classes[$class], $value);
    	
    	$this->_messages[] = $markup;
    }

    public function get(){
    	foreach($this->_messages as $value){
			$markup.= $value;
		}

		return $markup;
    }
}
?>
