<?php
class usuarios_Controller extends Qubit_Controller {
    
    
    public function init() {
        //
    }

   	public function add(){

    	$request = $this->getRequest();
    	$id = $request->getParam('id');

	    $this->view->id = $id ;
	    $this->view->nombre = '';
	    $this->view->puerto = '';

    	$this->view->setViewName('edit');
    }

    public function delete(){
    	$request = $this->getRequest();
    	$usuarios = Qubit_Loader::LoadModel('usuarios'); 
    	$id = $request->getParam('id');
    	$usuarios->delete("rut = $id");	
    	$this->redirect(array(action => 'index'),array(conf => 3));
    }

    public function update(){
    	$request = $this->getRequest();

    	$id = $request->getParam('id',0);
    	$usuarios = Qubit_Loader::LoadModel('usuarios'); 
        $db = Qubit_Db::getAdapter();

    	$data = array(
            rut             => $request->getParam('rut'),
            dv              => $request->getParam('dv'),
			nombre 			=> $request->getParam('nombre'),
			apellido 		=> $request->getParam('apellido'),
			administrador 	=> $request->getParam('administrador')
		);        
		
        if (strlen($request->getParam('password')) > 0){
            $pass = array(password => SHA1($request->getParam('password')));
            $data = array_merge($data,$pass);
        }

    	if ($id == 0){
    		
            $dataProcedure = array(
                rut             => $request->getParam('rut'),
                dv              => $request->getParam('dv'),
                nombre          => $request->getParam('nombre'),
                apellido        => $request->getParam('apellido'),
                password        => SHA1($request->getParam('password')),
                administrador   => $request->getParam('administrador')
            );  
            
            
            $db->call('pr_nuevo_usuario',$dataProcedure);
    	}
    	else{ 
			$usuarios->update($data,"rut = $id");
    	}
    	
    	$this->redirect(array(action => 'index'),array(conf => 1));
    }

    public function edit(){

    	$request = $this->getRequest();

    	$id = $request->getParam('id');

    	$usuarios = Qubit_Loader::LoadModel('usuarios'); 
    	$select = $usuarios->select();
		$select
		    ->from(array('u' =>'usuarios'),array('rut','dv','nombre','apellido','password','administrador'))
		    ->where("u.rut = $id");

		$row = $usuarios->fetchRow($select);
       
	    $this->view->id = $id;
        $this->view->dv = $row->dv;
	    $this->view->nombre = $row->nombre;
	    $this->view->apellido = $row->apellido;
	    $this->view->administrador = $row->administrador;
    }
    
    public function index() {
        //
    	$usuarios = Qubit_Loader::LoadModel('usuarios');
    	$request = $this->getRequest();

    	$select = $usuarios->select();

	    $select
		    ->from(array('u' =>'usuarios'),array('rut','nombre','apellido'))
		    ->order("u.rut");	

		$paginator = Qubit_Paginator::factory($select)
                ->setCurrentPageNumber($request->getParam('pag',1))
                ->setItemCountPerPage(5)
                ->setPageRange(7)
                ->assemble();

        $this->view->rstusuarios = $paginator->getItems();
	    $this->view->num_total_registros = $paginator->getItemCount();
        $this->view->paginator = $paginator;
    }
}
?>