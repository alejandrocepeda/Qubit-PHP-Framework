<?php
class home_Controller extends Qubit_Controller {
    
    
    public function init() {
        //
        $session = Qubit_Session::getInstance();
        $this->view->nombre_completo = $session->getSession('nombre') . ' ' . $session->getSession('apellido');     
    }
    
    public function index() {
        //
    }
}
?>