<?php
class Qubit_Json {
	public static $_instance = null;

	public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function encode($value){
    	return json_encode($value);
    }

	public function json($data){

		header('Content-Type: application/json');
		return $this->encode($data);
	}
}
?>