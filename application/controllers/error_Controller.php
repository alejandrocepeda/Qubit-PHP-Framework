<?php
class error_Controller extends Qubit_Controller {
    
    
    public function init() {
        $e = Qubit_Exception::getError_Handler();
        $type = Qubit_Exception::getType();
        $view = Qubit_Exception::getView();

        switch ($type) {
            case Qubit_Exception::EXCEPTION_NO_CONTROLLER:
                $this->view->message = 'No existe el controllador solicitado';
            case Qubit_Exception::EXCEPTION_NO_ACTION:
                $this->view->message = 'La acción que estas buscando no existe o fue removida';
                break;
            case Qubit_Exception::EXCEPTION_APP_ERROR:
                $this->view->message = 'Error en la Aplicación';
                break;
            default:
                $this->view->message = $e->getMessage();
                
                break;
        }
            
        $this->view->e = $e;
        $this->view->setViewName($view);
    }
    
    public function index() {
        //
    }
}
?>