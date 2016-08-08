<?php
class auth_Controller extends Qubit_Controller {
    
    public function init() {
        //
    }
    
    public function index() {
        //
    }

    public function authenticate(){
    	//
    	$ajax = $this->ajaxContext();
    	$request = $this->getRequest();

    	$username = $request->getParam('username');
		$password = $request->getParam('password');

    	$auth = Qubit_Auth::getInstance();
        $auth->setStorage(Qubit_Session::getInstance());
        
        $auth->setSecurity('SHA1');
        $auth->setDbAdapter(Qubit_Db::getAdapter());
        $auth->setTable('usuarios');
        $auth->setColumCredential('nombre','password');
        $auth->setValueCredential($username,$password);
        $auth->setResultRows(array('nombre','apellido','rut'));
        $auth->authenticate();
        
        if ($auth->IsOk()){
            $auth->SaveIdentity();
            $ajax->script("window.location='home'");
        }
        else{
        	$ajax->script("jQuery('#mensaje-box').removeClass('hide')");
        	$ajax->script("jQuery('#mensaje-text').html('Usuario o Contraseña invalidos')");
        } 
    }
}
?>