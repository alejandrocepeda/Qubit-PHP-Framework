<?php
class logout_Controller extends Qubit_Controller {
    
    
    public function init() {
        
        $auth = Qubit_Auth::getInstance();
        $auth->clearIdentity();

        $this->redirect(array('controller' => 'auth'));
    }
    
    public function index() {
        //
    }
}
?>