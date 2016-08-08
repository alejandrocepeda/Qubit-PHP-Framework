<?php 
class Qubit_Model {
	protected $_shemaName = '';
	public $_select = '';

	function __construct($name){
		$this->_shemaName = $name;

		$this->_select = new Qubit_Db_Select();  
	}

	public function select(){
		return  $this->_select->select();
	}


}

?>