<?php
class Qubit_View_Helper_getMensajeConf{
	
	public function getMensajeConf(){

		$request = Qubit_Request::getInstance();
		$FlashMessenger = Qubit_FlashMessenger::getInstance();
		
		if ($request->getParam('conf') == 1){
			$FlashMessenger->add('<strong>Bien hecho!</strong> Se actualizo con éxito el registro.','success');
		}
		elseif ($request->getParam('conf') == 2){
			$FlashMessenger->add('<strong>Bien hecho!</strong> Se agrego con éxito el registro.','success');
		}	
		elseif ($request->getParam('conf') == 3){
			$FlashMessenger->add('<strong>Bien hecho!</strong> Se borro con éxito el registro.','success');
		}	

		return $FlashMessenger->get();
	}
}
?>				