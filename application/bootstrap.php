<?php
class Bootstrap extends Qubit_Bootstrap{
    
    public function init(){
        //
        $request = Qubit_Request::getInstance();

        $controller_name = $request->getParam('url','index');

        if ($controller_name != 'auth'){  
            $auth = Qubit_Auth::getInstance();
            $auth->setStorage(Qubit_Session::getInstance());  

            if (!$auth->isValid()){
                $this->redirect(array('controller' => 'auth'));
            }   
        }
    }
    

    public function _initDatabase(){
       
        $db = Qubit_Db::factory('mysqli',array(
            host => '127.0.0.1',
            user => 'root',
            pwd => '',
            db_name => 'qubitframework',
            port => 3306));
        
        Qubit_Db::setAdapterDefault($db);
    }   

    public function _initView(){
        $view = Qubit_View::getInstance();
        
        $view->Doctype('XHTML5');
        
        $view->HeadTitle('Qubit PHP Framework')->separator(' | ');
        $view->HeadMeta(array(charset => 'utf-8'));  
        $view->HeadMeta(array(http-equiv => 'X-UA-Compatible',content => 'chrome=1'));  
        $view->HeadMeta(array(name => 'viewport',content => 'width=device-width, initial-scale=1 , maximum-scale=1'));  
        
        $view->HeadScript(array(src => 'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js'));
        //$view->HeadScript(array(src => 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'));
        $view->HeadScript(array(src => 'https://code.jquery.com/jquery-2.2.0.js '));
        
        $view->HeadScript(array(src => 'js/bootstrap.min.js'));
        $view->HeadScript(array(src => 'js/placeholder-shim.min.js'));
        $view->HeadScript(array(src => 'js/bootstrap-checkbox.min.js'));
        $view->HeadScript(array(src => 'js/jquery.qubit.ajax.js'));

        $view->headLink(array(rel => 'stylesheet',href => 'css/toolkit-inverse.css'));          
        $view->headLink(array(rel => 'stylesheet',href => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css')); 
        $view->headLink(array(rel => 'stylesheet',href => 'css/bootstrap.css')); 
        $view->headLink(array(rel => 'stylesheet',href => 'css/bootstrap.min.css')); 
        $view->headLink(array(rel => 'stylesheet',href => 'css/demo.css'));          
        $view->headLink(array(rel => 'stylesheet',href => 'css/login-theme-1.css'));          
        $view->headLink(array(rel => 'stylesheet',href => 'css/animate-custom.css'));          
    }
}
?>


